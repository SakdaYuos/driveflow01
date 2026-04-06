<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function checkout(Request $request, Car $car)
    {
        abort_unless($car->status === 'Available', 404);

        $startDate = $request->start      ?? now()->format('Y-m-d');
        $endDate   = $request->end        ?? now()->addDay()->format('Y-m-d');
        $startTime = $request->start_time ?? '10:00';
        $endTime   = $request->end_time   ?? '10:00';

        $start = Carbon::parse("{$startDate} {$startTime}");
        $end   = Carbon::parse("{$endDate} {$endTime}");
        $days  = max(1, (int) ceil($start->diffInHours($end) / 24));

        $subtotal   = $days * $car->price_per_day;
        $serviceFee = round($subtotal * 0.10, 2);
        $baseTotal  = $subtotal + $serviceFee;

        return view('customer.payment.checkout', compact(
            'car', 'startDate', 'endDate', 'startTime', 'endTime',
            'days', 'subtotal', 'serviceFee', 'baseTotal'
        ));
    }
}
