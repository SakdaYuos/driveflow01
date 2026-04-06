<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCars      = Car::count();
        $availableCars  = Car::where('status', 'Available')->count();
        $activeRentals  = Booking::whereIn('status', ['Confirmed', 'Ongoing'])->count();
        $todayBookings  = Booking::whereDate('created_at', Carbon::today())->count();
        $totalRevenue   = Booking::where('payment_status', 'Paid')->sum('total_price');
        $totalCustomers = User::where('is_admin', false)->count();

        $recentBookings = Booking::with(['customer', 'car'])
            ->latest()
            ->take(6)
            ->get();

        $carsByStatus = [
            'Available'   => Car::where('status', 'Available')->count(),
            'Rented'      => Car::where('status', 'Rented')->count(),
            'Maintenance' => Car::where('status', 'Maintenance')->count(),
        ];

        $revenueByCity = [];
        foreach (Car::CITIES as $city) {
            $cityCarIds = Car::where('city', $city)->pluck('id');
            $revenueByCity[$city] = Booking::whereIn('car_id', $cityCarIds)
                ->where('payment_status', 'Paid')
                ->sum('total_price');
        }

        $bookingsByStatus = [];
        foreach (Booking::STATUSES as $status) {
            $bookingsByStatus[$status] = Booking::where('status', $status)->count();
        }

        return view('admin.dashboard', compact(
            'totalCars', 'availableCars', 'activeRentals', 'todayBookings',
            'totalRevenue', 'totalCustomers', 'recentBookings',
            'carsByStatus', 'revenueByCity', 'bookingsByStatus'
        ));
    }
}
