@extends('layouts.admin')
@section('title','Bookings')
@section('page-title','Bookings')
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2><i class="fa fa-calendar-check" style="color:var(--red);margin-right:10px"></i>Booking Management</h2><p>Track and manage all rentals</p></div>
    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New Booking</a>
</div>
<form method="GET" action="{{ route('admin.bookings.index') }}">
<div class="toolbar"><div class="toolbar-left">
    <div class="search-group"><i class="fa fa-search"></i><input type="text" name="search" class="form-control" placeholder="Search customer or car…" value="{{ request('search') }}" style="width:240px;padding-left:36px"></div>
    <select name="status" class="form-select" style="width:140px"><option value="">All Status</option>@foreach(\App\Models\Booking::STATUSES as $s)<option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select>
    <select name="payment" class="form-select" style="width:130px"><option value="">All Payments</option><option value="Paid" {{ request('payment')=='Paid'?'selected':'' }}>Paid</option><option value="Unpaid" {{ request('payment')=='Unpaid'?'selected':'' }}>Unpaid</option></select>
    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
    @if(request()->hasAny(['search','status','payment']))<a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-light btn-sm"><i class="fa fa-xmark"></i> Clear</a>@endif
</div></div>
</form>
<div class="table-wrapper">
<table><thead><tr><th>#</th><th>Customer</th><th>Car</th><th>Pickup</th><th>Return</th><th>Days</th><th>Total</th><th>Payment</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@forelse($bookings as $booking)
@php
    $from = $booking->pickup_date ?? $booking->start_date;
    $to   = $booking->return_date ?? $booking->end_date;
    $customerName = $booking->user->name ?? '—';
    $total = $booking->total_price ?: $booking->total;
    $payStatus = $booking->payment_status ?? 'Unpaid';
    $bookStatus = $booking->status ?? ucfirst($booking->booking_status ?? 'Pending');
@endphp
<tr>
    <td style="color:var(--white-4);font-size:11px">#{{ $booking->id }}</td>
    <td><strong style="color:var(--white)">{{ $customerName }}</strong><div style="font-size:11px;color:var(--white-4)">{{ $booking->user->phone ?? '' }}</div></td>
    <td><span style="color:var(--white-2)">{{ $booking->car->name ?? '—' }}</span><div style="font-size:11px;color:var(--white-4)">{{ $booking->car->license_plate ?? '' }}</div></td>
    <td style="font-size:12px;color:var(--white-3)">{{ $from ? \Carbon\Carbon::parse($from)->format('d M Y') : '—' }}</td>
    <td style="font-size:12px;color:var(--white-3)">{{ $to ? \Carbon\Carbon::parse($to)->format('d M Y') : '—' }}</td>
    <td style="text-align:center;color:var(--white-3)">{{ $booking->days }}</td>
    <td><span style="font-family:'Barlow Condensed',sans-serif;font-size:18px;font-weight:700;color:var(--red)">${{ number_format($total,0) }}</span></td>
    <td><span class="badge badge-{{ strtolower($payStatus)==='paid'?'paid':'unpaid' }}">{{ ucfirst($payStatus) }}</span></td>
    <td>
        <form method="POST" action="{{ route('admin.bookings.status',$booking) }}">@csrf @method('PATCH')
            <select name="status" class="status-select" onchange="this.form.submit()">@foreach(\App\Models\Booking::STATUSES as $s)<option value="{{ $s }}" {{ $bookStatus==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select>
        </form>
    </td>
    <td>
        <div style="display:flex;gap:5px">
            <a href="{{ route('admin.bookings.show',$booking) }}" class="btn btn-outline-light btn-sm btn-icon"><i class="fa fa-eye"></i></a>
            <a href="{{ route('admin.bookings.edit',$booking) }}" class="btn btn-outline-light btn-sm btn-icon"><i class="fa fa-pen"></i></a>
            @if(!in_array($bookStatus,['Completed','Cancelled']))
            <form method="POST" action="{{ route('admin.bookings.cancel',$booking) }}">@csrf @method('PATCH')
                <button type="submit" class="btn btn-sm btn-icon" style="background:transparent;border:1px solid var(--border);color:var(--red);cursor:pointer" onclick="return confirm('Cancel this booking?')"><i class="fa fa-ban"></i></button>
            </form>
            @endif
        </div>
    </td>
</tr>
@empty
<tr><td colspan="10"><div class="empty-state"><i class="fa fa-calendar-xmark"></i><h5>No bookings found</h5><a href="{{ route('admin.bookings.create') }}" class="btn btn-primary btn-sm mt-3"><i class="fa fa-plus"></i> Create First Booking</a></div></td></tr>
@endforelse
</tbody></table>
</div>
<div style="margin-top:16px">{{ $bookings->links() }}</div>
@endsection
