<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class TripController extends Controller
{
    public function index()
    {
        $bookings = Booking::forUser(auth()->id())
            ->with('car')
            ->latest()
            ->paginate(10);

        return view('customer.trips.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        $booking->load('car');
        return view('customer.trips.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_unless(in_array($booking->booking_status, ['pending', 'confirmed']), 422);

        $booking->update([
            'booking_status' => 'cancelled',
            'status'         => 'Cancelled',
        ]);

        if ($booking->rate_type === 'refundable') {
            $booking->update(['payment_status' => 'refunded']);
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }
}
