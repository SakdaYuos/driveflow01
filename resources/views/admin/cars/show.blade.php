@extends('layouts.admin')
@section('title',$car->name)
@section('page-title','Car Detail')
@section('page-subtitle',$car->name)
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2><i class="fa fa-car" style="color:var(--red);margin-right:10px"></i>{{ $car->name }}</h2><p>{{ $car->brand }} {{ $car->model }} · {{ $car->year }}</p></div>
    <div style="display:flex;gap:8px"><a href="{{ route('admin.cars.edit',$car) }}" class="btn btn-primary"><i class="fa fa-pen"></i> Edit Car</a><a href="{{ route('admin.cars.index') }}" class="btn btn-outline-light"><i class="fa fa-arrow-left"></i> Back</a></div>
</div>
<div class="grid-1-2" style="align-items:start">
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="car-image-wrap" style="height:260px">
            @if($car->image)<img src="{{ asset('storage/'.$car->image) }}">@else<i class="fa fa-car placeholder-icon"></i>@endif
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-circle-info"></i> Quick Info</div></div>
            <div class="card-body">
                @foreach([['Status','',$car->status,'badge badge-'.strtolower($car->status)],['Type','',$car->type,'badge badge-'.strtolower($car->type)],['Fuel Type','',$car->fuel_type,''],['Seats','',$car->car_seat.' seats',''],['City','',$car->city,''],['Plate','',$car->license_plate,''],['Price/Day','$'.number_format($car->price_per_day,0).'/day','','']] as [$label,$val,$badge,$cls])
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border)">
                    <span style="font-size:12px;color:var(--white-3)">{{ $label }}</span>
                    @if($cls)<span class="{{ $cls }}">{{ $badge }}</span>@elseif($badge)<span style="font-size:13px;color:var(--white)">{{ $badge }}</span>@else<span style="font-family:'Barlow Condensed',sans-serif;font-size:22px;font-weight:700;color:var(--red)">{{ $val }}</span>@endif
                </div>
                @endforeach
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-rotate"></i> Update Status</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cars.status',$car) }}">@csrf @method('PATCH')
                    <div style="display:flex;gap:8px">
                        <select name="status" class="form-select" style="flex:1">@foreach(\App\Models\Car::STATUSES as $s)<option value="{{ $s }}" {{ $car->status==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fa fa-calendar-check"></i> Booking History</div><span style="font-size:12px;color:var(--white-3)">{{ $car->bookings->count() }} total</span></div>
        <div class="table-wrapper" style="border:none;border-radius:0">
            <table><thead><tr><th>#</th><th>Customer</th><th>Pickup</th><th>Return</th><th>Total</th><th>Payment</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($car->bookings->sortByDesc('created_at') as $booking)
            <tr>
                <td style="color:var(--white-4);font-size:11px">{{ $booking->id }}</td>
                <td style="color:var(--white)">{{ $booking->user->name ?? '—' }}</td>
                <td style="color:var(--white-3);font-size:12px">{{ $booking->pickup_date ? \Carbon\Carbon::parse($booking->pickup_date)->format('d M Y') : ($booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d M Y') : '—') }}</td>
                <td style="color:var(--white-3);font-size:12px">{{ $booking->return_date ? \Carbon\Carbon::parse($booking->return_date)->format('d M Y') : ($booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d M Y') : '—') }}</td>
                <td style="font-family:'Barlow Condensed',sans-serif;font-size:16px;color:var(--red)">${{ number_format($booking->total_price ?: $booking->total,0) }}</td>
                <td><span class="badge badge-{{ $booking->payment_status==='Paid'?'paid':'unpaid' }}">{{ $booking->payment_status }}</span></td>
                <td><span class="badge badge-{{ strtolower($booking->status ?? $booking->booking_status) }}">{{ $booking->status ?? ucfirst($booking->booking_status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:30px;color:var(--white-4)">No booking history</td></tr>
            @endforelse
            </tbody></table>
        </div>
    </div>
</div>
@endsection
