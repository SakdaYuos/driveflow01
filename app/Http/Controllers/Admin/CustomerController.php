<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)->withCount('bookings');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhere('phone', 'like', "%$q%");
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'phone'    => 'required|string|max:20',
            'email'    => 'nullable|email|max:100|unique:users,email',
            'password' => 'nullable|string|min:8|confirmed',
            'address'  => 'nullable|string|max:255',
            'city'     => 'nullable|in:Phnom Penh,Siem Reap,Poi Pet,Sihanoukville',
        ]);

        $data['password'] = empty($data['password'])
            ? Hash::make('password123')
            : Hash::make($data['password']);
        $data['is_admin'] = false;

        User::create($data);

        return redirect()->route('admin.customers.index')->with('success', 'Customer added successfully.');
    }

    public function show(User $customer)
    {
        $customer->load('bookings.car');
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(User $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'phone'    => 'required|string|max:20',
            'email'    => 'nullable|email|max:100|unique:users,email,' . $customer->id,
            'password' => 'nullable|string|min:8|confirmed',
            'address'  => 'nullable|string|max:255',
            'city'     => 'nullable|in:Phnom Penh,Siem Reap,Poi Pet,Sihanoukville',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $customer->update($data);

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(User $customer)
    {
        if ($customer->bookings()->whereIn('booking_status', ['confirmed', 'active'])->exists()) {
            return back()->with('error', 'Cannot delete customer with active bookings.');
        }
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
