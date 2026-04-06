<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Car;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCars = Car::available()->orderByDesc('created_at')->get();

        $cities = Car::available()->distinct()->orderBy('city')->pluck('city');

        $stats = [
            'total_cars'   => Car::available()->count(),
            'total_cities' => $cities->count(),
            'avg_rating'   => round(Car::available()->avg('rating') ?? 0, 1),
        ];

        return view('customer.home.index', compact('featuredCars', 'cities', 'stats'));
    }
}
