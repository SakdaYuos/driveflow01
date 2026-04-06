<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'car', 'user']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function($sub) use ($q) {
                $sub->whereHas('user', fn($c) => $c->where('name', 'like', "%$q%"))
                    ->orWhereHas('car',  fn($c) => $c->where('name', 'like', "%$q%"))
                    ->orWhere('booking_number', 'like', "%$q%");
            });
        }

        if ($request->filled('status'))  $query->where('status', $request->status);
        if ($request->filled('payment')) $query->where('payment_status', $request->payment);

        $bookings = $query->latest()->paginate(10)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $customers = User::where('is_admin', false)->orderBy('name')->get();
        $cars      = Car::where('status', 'Available')->orderBy('name')->get();
        return view('admin.bookings.create', compact('customers', 'cars'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'    => 'required|exists:users,id',
            'car_id'         => 'required|exists:cars,id',
            'pickup_date'    => 'required|date|after_or_equal:today',
            'return_date'    => 'required|date|after:pickup_date',
            'status'         => 'required|in:Pending,Confirmed,Ongoing,Completed,Cancelled',
            'payment_status' => 'required|in:Paid,Unpaid',
            'notes'          => 'nullable|string|max:500',
        ]);

        $car  = Car::findOrFail($data['car_id']);
        $days = Carbon::parse($data['pickup_date'])->diffInDays($data['return_date']);
        $data['total_price'] = max(1, $days) * $car->price_per_day;
        $data['user_id']     = $data['customer_id'];

        if ($data['status'] === 'Ongoing') {
            $car->update(['status' => 'Rented']);
        }

        Booking::create($data);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking created successfully.');
    }

    public function show(Booking $booking)
    {
        $booking->load(['customer', 'car', 'user']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $customers = User::where('is_admin', false)->orderBy('name')->get();
        $cars      = Car::orderBy('name')->get();
        return view('admin.bookings.edit', compact('booking', 'customers', 'cars'));
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'customer_id'    => 'required|exists:users,id',
            'car_id'         => 'required|exists:cars,id',
            'pickup_date'    => 'required|date',
            'return_date'    => 'required|date|after:pickup_date',
            'status'         => 'required|in:Pending,Confirmed,Ongoing,Completed,Cancelled',
            'payment_status' => 'required|in:Paid,Unpaid',
            'notes'          => 'nullable|string|max:500',
        ]);

        $car  = Car::findOrFail($data['car_id']);
        $days = Carbon::parse($data['pickup_date'])->diffInDays($data['return_date']);
        $data['total_price'] = max(1, $days) * $car->price_per_day;
        $data['user_id']     = $data['customer_id'];

        $this->handleCarStatus($booking, $car, $booking->status, $data['status']);
        $booking->update($data);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        if ($booking->status === 'Ongoing') {
            $booking->car->update(['status' => 'Available']);
        }
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:Pending,Confirmed,Ongoing,Completed,Cancelled']);
        $this->handleCarStatus($booking, $booking->car, $booking->status, $request->status);
        $booking->update(['status' => $request->status]);
        return back()->with('success', "Booking status updated to {$request->status}.");
    }

    public function cancel(Request $request, Booking $booking)
    {
        if (in_array($booking->status, ['Completed', 'Cancelled'])) {
            return back()->with('error', 'Cannot cancel this booking.');
        }
        if ($booking->status === 'Ongoing') {
            $booking->car->update(['status' => 'Available']);
        }
        $booking->update(['status' => 'Cancelled']);
        return back()->with('success', 'Booking cancelled.');
    }

    private function handleCarStatus(Booking $booking, Car $car, string $oldStatus, string $newStatus): void
    {
        if ($newStatus === 'Ongoing' && $oldStatus !== 'Ongoing') {
            $car->update(['status' => 'Rented']);
        }
        if (in_array($newStatus, ['Completed', 'Cancelled']) && $oldStatus === 'Ongoing') {
            $car->update(['status' => 'Available']);
        }
    }
}
