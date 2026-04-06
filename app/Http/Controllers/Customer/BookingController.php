<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request, Car $car)
    {
        abort_unless($car->status === 'Available', 404);

        $v = $request->validate([
            'start_date'            => 'required|date|after_or_equal:today',
            'end_date'              => 'required|date|after:start_date',
            'start_time'            => 'required',
            'end_time'              => 'required',
            'pickup_option'         => 'required|in:self,delivery',
            'rate_type'             => 'required|in:non_refundable,refundable',
            'driver_first_name'     => 'required|string|max:100',
            'driver_last_name'      => 'required|string|max:100',
            'driver_phone'          => 'required|string|max:30',
            'driver_email'          => 'required|email|max:150',
            'driver_license'        => 'required|string|max:50',
            'driver_license_expiry' => 'required|date|after:today',
            'payment_method'        => 'required|in:card,aba',
        ]);

        $start = Carbon::parse("{$v['start_date']} {$v['start_time']}");
        $end   = Carbon::parse("{$v['end_date']} {$v['end_time']}");
        $days  = max(1, (int) ceil($start->diffInHours($end) / 24));

        $subtotal    = $days * $car->price_per_day;
        $serviceFee  = round($subtotal * 0.10, 2);
        $deliveryFee = $v['pickup_option'] === 'delivery' ? 10.00 : 0.00;
        $rateExtra   = $v['rate_type']     === 'refundable' ? 15.00 : 0.00;
        $total       = $subtotal + $serviceFee + $deliveryFee + $rateExtra;

        $booking = Booking::create([
            'booking_number'        => Booking::generateNumber(),
            'user_id'               => auth()->id(),
            'car_id'                => $car->id,
            'start_date'            => $v['start_date'],
            'end_date'              => $v['end_date'],
            'start_time'            => $v['start_time'],
            'end_time'              => $v['end_time'],
            'days'                  => $days,
            'pickup_option'         => $v['pickup_option'],
            'rate_type'             => $v['rate_type'],
            'driver_first_name'     => $v['driver_first_name'],
            'driver_last_name'      => $v['driver_last_name'],
            'driver_phone'          => $v['driver_phone'],
            'driver_email'          => $v['driver_email'],
            'driver_license'        => $v['driver_license'],
            'driver_license_expiry' => $v['driver_license_expiry'],
            'subtotal'              => $subtotal,
            'service_fee'           => $serviceFee,
            'delivery_fee'          => $deliveryFee,
            'rate_extra'            => $rateExtra,
            'total'                 => $total,
            'payment_method'        => $v['payment_method'],
            'payment_status'        => 'pending',
            'booking_status'        => 'confirmed',
            // mirror for admin panel
            'pickup_date'           => $v['start_date'],
            'return_date'           => $v['end_date'],
            'total_price'           => $total,
            'status'                => 'Confirmed',
        ]);

        $car->increment('trips_count');

        return redirect()
            ->route('booking.confirm', $booking)
            ->with('success', 'Booking confirmed!');
    }

    public function confirm(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        $booking->load('car');
        return view('customer.payment.confirm', compact('booking'));
    }
}
