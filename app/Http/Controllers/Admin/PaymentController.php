<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['car', 'user']);

        if ($request->filled('payment')) $query->where('payment_status', $request->payment);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->whereHas('user', fn($c) => $c->where('name', 'like', "%$q%"));
        }

        $bookings     = $query->latest()->paginate(10)->withQueryString();
        $totalPaid    = Booking::where('payment_status', 'Paid')->sum('total_price');
        $totalUnpaid  = Booking::where('payment_status', 'Unpaid')->sum('total_price');
        $totalRevenue = $totalPaid;

        return view('admin.payments.index', compact('bookings', 'totalPaid', 'totalUnpaid', 'totalRevenue'));
    }

    public function toggle(Booking $booking)
    {
        $newStatus = ($booking->payment_status === 'Paid') ? 'Unpaid' : 'Paid';
        $booking->update(['payment_status' => $newStatus]);
        return back()->with('success', "Payment marked as {$newStatus}.");
    }
}
