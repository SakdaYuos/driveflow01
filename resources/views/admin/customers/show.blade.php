@extends('layouts.admin')
@section('title',$customer->name)
@section('page-title','Customer Detail')
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2><i class="fa fa-user" style="color:var(--red);margin-right:10px"></i>{{ $customer->name }}</h2><p>Customer profile & rental history</p></div>
    <div style="display:flex;gap:8px"><a href="{{ route('admin.customers.edit',$customer) }}" class="btn btn-primary"><i class="fa fa-pen"></i> Edit</a><a href="{{ route('admin.customers.index') }}" class="btn btn-outline-light"><i class="fa fa-arrow-left"></i> Back</a></div>
</div>
<div class="grid-1-2" style="align-items:start">
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fa fa-id-card"></i> Profile</div></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
            <div style="display:flex;align-items:center;gap:14px;padding-bottom:14px;border-bottom:1px solid var(--border)">
                <div style="width:52px;height:52px;background:var(--red);border-radius:2px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:22px">{{ strtoupper(substr($customer->name,0,1)) }}</div>
                <div><div style="font-size:18px;font-weight:700;color:var(--white)">{{ $customer->name }}</div><div style="font-size:12px;color:var(--white-3)">Customer ID #{{ $customer->id }}</div></div>
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;font-size:13px">
                @if($customer->phone)<div><i class="fa fa-phone" style="color:var(--red);margin-right:8px"></i>{{ $customer->phone }}</div>@endif
                @if($customer->email)<div><i class="fa fa-envelope" style="color:var(--red);margin-right:8px"></i>{{ $customer->email }}</div>@endif
                @if($customer->city)<div><i class="fa fa-location-dot" style="color:var(--red);margin-right:8px"></i>{{ $customer->city }}</div>@endif
                @if($customer->address)<div><i class="fa fa-map-marker" style="color:var(--red);margin-right:8px"></i>{{ $customer->address }}</div>@endif
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;padding-top:14px;border-top:1px solid var(--border)">
                <div style="text-align:center;background:var(--black-3);padding:12px;border:1px solid var(--border)"><div style="font-family:'Barlow Condensed',sans-serif;font-size:28px;font-weight:700;color:var(--white)">{{ $customer->bookings->count() }}</div><div style="font-size:10px;letter-spacing:1px;text-transform:uppercase;color:var(--white-3)">Total Bookings</div></div>
                <div style="text-align:center;background:var(--black-3);padding:12px;border:1px solid var(--border)"><div style="font-family:'Barlow Condensed',sans-serif;font-size:28px;font-weight:700;color:var(--red)">${{ number_format($customer->bookings->sum('total_price'),0) }}</div><div style="font-size:10px;letter-spacing:1px;text-transform:uppercase;color:var(--white-3)">Total Spent</div></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fa fa-calendar-check"></i> Rental History</div></div>
        <div class="table-wrapper" style="border:none;border-radius:0">
            <table><thead><tr><th>#</th><th>Car</th><th>Pickup</th><th>Return</th><th>Total</th><th>Payment</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($customer->bookings->sortByDesc('created_at') as $b)
            <tr>
                <td style="color:var(--white-4);font-size:11px">{{ $b->id }}</td>
                <td style="color:var(--white-2)">{{ $b->car->name ?? '—' }}</td>
                <td style="color:var(--white-3);font-size:12px">{{ $b->pickup_date ? \Carbon\Carbon::parse($b->pickup_date)->format('d M Y') : ($b->start_date ? \Carbon\Carbon::parse($b->start_date)->format('d M Y') : '—') }}</td>
                <td style="color:var(--white-3);font-size:12px">{{ $b->return_date ? \Carbon\Carbon::parse($b->return_date)->format('d M Y') : ($b->end_date ? \Carbon\Carbon::parse($b->end_date)->format('d M Y') : '—') }}</td>
                <td style="font-family:'Barlow Condensed',sans-serif;font-size:16px;color:var(--red)">${{ number_format($b->total_price ?: $b->total,0) }}</td>
                <td><span class="badge badge-{{ ($b->payment_status==='Paid'||$b->payment_status==='paid')?'paid':'unpaid' }}">{{ $b->payment_status }}</span></td>
                <td><span class="badge badge-{{ strtolower($b->status ?? $b->booking_status) }}">{{ $b->status ?? ucfirst($b->booking_status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:30px;color:var(--white-4)">No bookings yet</td></tr>
            @endforelse
            </tbody></table>
        </div>
    </div>
</div>
@endsection
