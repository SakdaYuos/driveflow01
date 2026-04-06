@extends('layouts.admin')
@section('title','Payments')
@section('page-title','Payments')
@section('content')
<div class="page-header"><div class="page-header-title"><h2><i class="fa fa-credit-card" style="color:var(--red);margin-right:10px"></i>Payment Records</h2><p>Track all rental payments</p></div></div>
<div class="grid-3 mb-4">
    <div class="stat-card" style="border-top-color:var(--success)"><i class="fa fa-circle-check stat-icon"></i><div class="stat-value" style="color:var(--success);font-size:32px">${{ number_format($totalPaid,0) }}</div><div class="stat-label">Total Paid</div></div>
    <div class="stat-card" style="border-top-color:var(--red)"><i class="fa fa-clock stat-icon"></i><div class="stat-value" style="font-size:32px">${{ number_format($totalUnpaid,0) }}</div><div class="stat-label">Total Unpaid</div></div>
    <div class="stat-card"><i class="fa fa-dollar-sign stat-icon"></i><div class="stat-value" style="font-size:32px;color:#60a5fa">${{ number_format($totalPaid+$totalUnpaid,0) }}</div><div class="stat-label">Grand Total</div></div>
</div>
<form method="GET" action="{{ route('admin.payments.index') }}">
<div class="toolbar"><div class="toolbar-left">
    <div class="search-group"><i class="fa fa-search"></i><input type="text" name="search" class="form-control" placeholder="Search customer name…" value="{{ request('search') }}" style="width:240px;padding-left:36px"></div>
    <select name="payment" class="form-select" style="width:140px"><option value="">All Payments</option><option value="Paid" {{ request('payment')=='Paid'?'selected':'' }}>Paid</option><option value="Unpaid" {{ request('payment')=='Unpaid'?'selected':'' }}>Unpaid</option></select>
    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
    @if(request()->hasAny(['search','payment']))<a href="{{ route('admin.payments.index') }}" class="btn btn-outline-light btn-sm"><i class="fa fa-xmark"></i> Clear</a>@endif
</div></div>
</form>
<div class="table-wrapper">
<table><thead><tr><th>Booking #</th><th>Customer</th><th>Car</th><th>Dates</th><th>Days</th><th>Total Price</th><th>Payment</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
@forelse($bookings as $booking)
@php $from=$booking->pickup_date??$booking->start_date; $to=$booking->return_date??$booking->end_date; @endphp
<tr>
    <td style="color:var(--white-4);font-size:11px"><a href="{{ route('admin.bookings.show',$booking) }}" style="color:var(--red-light)">#{{ $booking->id }}</a></td>
    <td style="font-weight:600;color:var(--white)">{{ $booking->user->name ?? '—' }}</td>
    <td style="color:var(--white-2)">{{ $booking->car->name ?? '—' }}</td>
    <td style="font-size:12px;color:var(--white-3)">{{ $from?\Carbon\Carbon::parse($from)->format('d M'):'—' }} → {{ $to?\Carbon\Carbon::parse($to)->format('d M Y'):'—' }}</td>
    <td style="text-align:center;color:var(--white-3)">{{ $booking->days }}</td>
    <td><span style="font-family:'Barlow Condensed',sans-serif;font-size:20px;font-weight:700;color:var(--red)">${{ number_format($booking->total_price?:$booking->total,2) }}</span></td>
    <td><span class="badge badge-{{ (strtolower($booking->payment_status)==='paid')?'paid':'unpaid' }}">{{ ucfirst($booking->payment_status) }}</span></td>
    <td><span class="badge badge-{{ strtolower($booking->status??$booking->booking_status) }}">{{ ucfirst($booking->status??$booking->booking_status) }}</span></td>
    <td>
        <form method="POST" action="{{ route('admin.payments.toggle',$booking) }}">@csrf @method('PATCH')
            <button type="submit" class="btn btn-sm {{ strtolower($booking->payment_status)==='paid'?'btn-outline-danger':'btn-success' }}">
                @if(strtolower($booking->payment_status)==='paid')<i class="fa fa-xmark"></i> Unpaid@else<i class="fa fa-check"></i> Paid@endif
            </button>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="9"><div class="empty-state"><i class="fa fa-credit-card"></i><h5>No payment records</h5></div></td></tr>
@endforelse
</tbody></table>
</div>
<div style="margin-top:16px">{{ $bookings->links() }}</div>
@endsection
