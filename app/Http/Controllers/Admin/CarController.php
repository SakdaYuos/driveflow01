<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%$q%")
                   ->orWhere('brand', 'like', "%$q%")
                   ->orWhere('model', 'like', "%$q%")
                   ->orWhere('license_plate', 'like', "%$q%");
            });
        }

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('city'))   $query->where('city', $request->city);
        if ($request->filled('type'))   $query->where('type', $request->type);

        $cars = $query->latest()->paginate(10)->withQueryString();

        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand'         => 'required|string|max:60',
            'model'         => 'required|string|max:60',
            'year'          => 'required|integer|min:1990|max:2030',
            'license_plate' => 'required|string|max:20|unique:cars,license_plate',
            'price_per_day' => 'required|numeric|min:1',
            'status'        => 'required|in:Available,Rented,Maintenance',
            'type'          => 'required|in:Sedan,SUV,Van',
            'city'          => 'required|in:Phnom Penh,Siem Reap,Poi Pet,Sihanoukville',
            'fuel_type'     => 'required|in:Petrol,Diesel,Electric,Hybrid',
            'car_seat'      => 'required|string|max:5',
            'description'   => 'nullable|string|max:500',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        Car::create($data);

        return redirect()->route('admin.cars.index')->with('success', 'Car added successfully.');
    }

    public function show(Car $car)
    {
        $car->load('bookings.customer');
        return view('admin.cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $data = $request->validate([
            'brand'         => 'required|string|max:60',
            'model'         => 'required|string|max:60',
            'year'          => 'required|integer|min:1990|max:2030',
            'license_plate' => 'required|string|max:20|unique:cars,license_plate,' . $car->id,
            'price_per_day' => 'required|numeric|min:1',
            'status'        => 'required|in:Available,Rented,Maintenance',
            'type'          => 'required|in:Sedan,SUV,Van',
            'city'          => 'required|in:Phnom Penh,Siem Reap,Poi Pet,Sihanoukville',
            'fuel_type'     => 'required|in:Petrol,Diesel,Electric,Hybrid',
            'car_seat'      => 'required|string|max:5',
            'description'   => 'nullable|string|max:500',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($car->image) Storage::disk('public')->delete($car->image);
            $data['image'] = $request->file('image')->store('cars', 'public');
        }

        $car->update($data);

        return redirect()->route('admin.cars.index')->with('success', 'Car updated successfully.');
    }

    public function destroy(Car $car)
    {
        if ($car->bookings()->whereIn('status', ['Confirmed', 'Ongoing'])->exists()) {
            return back()->with('error', 'Cannot delete car with active bookings.');
        }

        if ($car->image) Storage::disk('public')->delete($car->image);
        $car->delete();

        return redirect()->route('admin.cars.index')->with('success', 'Car deleted successfully.');
    }

    public function updateStatus(Request $request, Car $car)
    {
        $request->validate(['status' => 'required|in:Available,Rented,Maintenance']);
        $car->update(['status' => $request->status]);
        return back()->with('success', 'Car status updated.');
    }
}
