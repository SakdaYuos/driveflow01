<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $cars = Car::available()
            ->byCity($request->city)
            ->byBrands(array_filter((array) $request->brands))
            ->byFuels(array_filter((array) $request->fuels))
            ->byTypes(array_filter((array) $request->types))
            ->byPickups(array_filter((array) $request->pickups))
            ->bySeats($request->seats)
            ->maxPrice($request->max_price ?: 9999)
            ->sorted($request->sort)
            ->paginate(12)
            ->withQueryString();

        $cities     = Car::available()->distinct()->orderBy('city')->pluck('city');
        $brands     = Car::available()->distinct()->orderBy('brand')->pluck('brand');
        $types      = Car::available()->distinct()->orderBy('type')->pluck('type');
        $maxDbPrice = (int) Car::available()->max('price_per_day') ?: 500;

        return view('customer.cars.index', compact('cars', 'cities', 'brands', 'types', 'maxDbPrice'));
    }

    public function show(Car $car)
    {
        abort_unless($car->status === 'Available', 404);

        $reviews = $car->bookings()
            ->where('booking_status', 'completed')
            ->whereNotNull('review_text')
            ->with('user:id,name')
            ->latest()
            ->limit(5)
            ->get();

        $relatedCars = Car::available()
            ->where('city', $car->city)
            ->where('id', '!=', $car->id)
            ->limit(3)
            ->get();

        return view('customer.cars.show', compact('car', 'reviews', 'relatedCars'));
    }
}
