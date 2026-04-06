@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')
<div class="grid-4 mb-4">
    <div class="stat-card"><i class="fa fa-car stat-icon"></i><div class="stat-value">{{ $totalCars }}</div><div class="stat-label">Total Cars</div></div>
    <div class="stat-card"><i class="fa fa-circle-check stat-icon"></i><div class="stat-value" style="color:var(--success)">{{ $availableCars }}</div><div class="stat-label">Available</div></div>
    <div class="stat-card"><i class="fa fa-road stat-icon"></i><div class="stat-value" style="color:var(--red)">{{ $activeRentals }}</div><div class="stat-label">Active Rentals</div></div>
    <div class="stat-card"><i class="fa fa-calendar-day stat-icon"></i><div class="stat-value" style="color:var(--warning)">{{ $todayBookings }}</div><div class="stat-label">Today's Bookings</div></div>
</div>
<div class="grid-3 mb-4">
    <div class="stat-card"><i class="fa fa-dollar-sign stat-icon"></i><div class="stat-value" style="color:var(--success);font-size:32px">${{ number_format($totalRevenue,0) }}</div><div class="stat-label">Total Revenue (Paid)</div></div>
    <div class="stat-card"><i class="fa fa-users stat-icon"></i><div class="stat-value">{{ $totalCustomers }}</div><div class="stat-label">Customers</div></div>
    <div class="stat-card"><i class="fa fa-ban stat-icon"></i><div class="stat-value" style="color:var(--white-3)">{{ $bookingsByStatus['Cancelled'] ?? 0 }}</div><div class="stat-label">Cancelled</div></div>
</div>
<div class="grid-2-1" style="gap:20px">
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fa fa-clock-rotate-left"></i> Recent Bookings</div><a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-light btn-sm">View All</a></div>
        <div class="table-wrapper" style="border:none;border-radius:0">
            <table><thead><tr><th>#</th><th>Customer</th><th>Car</th><th>Total</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($recentBookings as $b)
            <tr>
                <td style="color:var(--white-4);font-size:11px">#{{ $b->id }}</td>
                <td><span style="font-weight:600;color:var(--white)">{{ $b->user->name ?? '—' }}</span></td>
                <td style="color:var(--white-3)">{{ $b->car->name ?? '—' }}</td>
                <td style="font-family:'Barlow Condensed',sans-serif;font-size:16px;color:var(--red)">${{ number_format($b->total_price ?: $b->total,0) }}</td>
                <td><span class="badge badge-{{ strtolower($b->status ?? $b->booking_status) }}">{{ $b->status ?? ucfirst($b->booking_status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;padding:30px;color:var(--white-4)">No bookings yet</td></tr>
            @endforelse
            </tbody></table>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-chart-pie"></i> Fleet Status</div></div>
            <div class="card-body">
                @php $colors=['Available'=>'var(--success)','Rented'=>'var(--red)','Maintenance'=>'var(--warning)']; $total=max(1,$totalCars); @endphp
                @foreach($carsByStatus as $status => $count)
                <div class="rev-bar" style="margin-bottom:12px">
                    <div class="rev-label" style="color:{{ $colors[$status]??'var(--white-3)' }}"><span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:{{ $colors[$status]??'#666' }};margin-right:6px"></span>{{ $status }}</div>
                    <div class="rev-track"><div class="rev-fill" style="width:{{ round($count/$total*100) }}%;background:{{ $colors[$status]??'var(--red)' }}"></div></div>
                    <div class="rev-val">{{ $count }}</div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-location-dot"></i> Revenue by City</div></div>
            <div class="card-body">
                @php $maxRev=max(1,max(array_values($revenueByCity))); @endphp
                @foreach($revenueByCity as $city => $rev)
                <div class="rev-bar" style="margin-bottom:10px">
                    <div class="rev-label" style="font-size:11px">{{ $city }}</div>
                    <div class="rev-track"><div class="rev-fill" style="width:{{ round($rev/$maxRev*100) }}%"></div></div>
                    <div class="rev-val" style="font-size:12px">${{ number_format($rev,0) }}</div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-bolt"></i> Quick Actions</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px">
                <a href="{{ route('admin.cars.create') }}" class="btn btn-outline-light" style="justify-content:flex-start"><i class="fa fa-plus" style="color:var(--red)"></i> Add New Car</a>
                <a href="{{ route('admin.bookings.create') }}" class="btn btn-outline-light" style="justify-content:flex-start"><i class="fa fa-calendar-plus" style="color:var(--red)"></i> New Booking</a>
                <a href="{{ route('admin.customers.create') }}" class="btn btn-outline-light" style="justify-content:flex-start"><i class="fa fa-user-plus" style="color:var(--red)"></i> Add Customer</a>
            </div>
        </div>
    </div>
</div>
@endsection
