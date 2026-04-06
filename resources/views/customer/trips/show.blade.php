@extends('layouts.app')
@section('title','Trip '.$booking->booking_number)
@push('styles')
<style>
.trip-layout{max-width:900px;margin:0 auto;padding:32px 24px}
.trip-card{background:var(--bg-card);border:1px solid var(--border);border-radius:14px;overflow:hidden;margin-bottom:20px}
.trip-card-head{padding:16px 20px;border-bottom:1px solid var(--border);font-size:12px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:var(--text-muted)}
.trip-card-body{padding:20px}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.info-label{font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px}
.info-val{font-size:14px;font-weight:600}
.price-row{display:flex;justify-content:space-between;font-size:13px;color:var(--text-muted);padding:6px 0}
.price-row strong{color:var(--text)}
.price-total{display:flex;justify-content:space-between;font-size:16px;font-weight:700;padding-top:12px;border-top:1px solid var(--border);margin-top:6px}
.price-total span{color:var(--red);font-size:20px}
@media(max-width:600px){.info-grid{grid-template-columns:1fr;}}
</style>
@endpush
@section('content')
<div style="border-bottom:1px solid var(--border);padding:12px 24px;font-size:12px;color:var(--text-muted)">
    <a href="{{ route('home') }}" class="text-red">Home</a> / <a href="{{ route('trips.index') }}" class="text-red">My Trips</a> / {{ $booking->booking_number }}
</div>
<div class="trip-layout">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:28px">
        <div>
            <div style="font-size:13px;color:var(--text-muted);margin-bottom:4px">Booking #{{ $booking->booking_number }}</div>
            <div style="font-family:'Barlow Condensed',sans-serif;font-size:28px;font-weight:800;line-height:1">Trip Details</div>
            <div style="font-size:12px;color:var(--text-muted);margin-top:6px">Booked on {{ $booking->created_at->format('M d, Y \a\t h:i A') }}</div>
        </div>
        @php $scls=match($booking->booking_status){'confirmed'=>'status-confirmed','pending'=>'status-pending','cancelled'=>'status-cancelled','completed'=>'status-completed','active'=>'status-active',default=>'status-pending'}; @endphp
        <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 16px;border-radius:20px;font-size:13px;font-weight:600;background:{{ match($booking->booking_status){'confirmed','active'=>'rgba(34,197,94,.12)','cancelled'=>'rgba(220,38,38,.12)','completed'=>'rgba(99,102,241,.12)',default=>'rgba(234,179,8,.12)'} }};color:{{ match($booking->booking_status){'confirmed','active'=>'#22c55e','cancelled'=>'var(--red)','completed'=>'#6366f1',default=>'#eab308'} }};border:1px solid {{ match($booking->booking_status){'confirmed','active'=>'rgba(34,197,94,.3)','cancelled'=>'rgba(220,38,38,.3)','completed'=>'rgba(99,102,241,.3)',default=>'rgba(234,179,8,.3)'} }}">{{ ucfirst($booking->booking_status) }}</span>
    </div>
    @if(session('success'))<div style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3);color:#22c55e;padding:12px 16px;border-radius:10px;font-size:13px;margin-bottom:20px">✓ {{ session('success') }}</div>@endif
    <div class="trip-card">
        <div class="trip-card-head">🚗 Vehicle</div>
        <div class="trip-card-body">
            <div style="display:flex;gap:16px;align-items:center">
                <div style="width:100px;height:70px;border-radius:8px;background:#111;overflow:hidden;flex-shrink:0">
                    @if($booking->car?->image)<img src="{{ asset('storage/'.$booking->car->image) }}" style="width:100%;height:100%;object-fit:cover">@else<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:32px">🚗</div>@endif
                </div>
                <div>
                    <div style="font-size:18px;font-weight:700;margin-bottom:4px">{{ $booking->car?->brand }} {{ $booking->car?->model }}</div>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;font-size:12px;color:var(--text-muted)">
                        <span>📅 {{ $booking->car?->year }}</span><span>⛽ {{ $booking->car?->fuel_type }}</span><span>🚘 {{ $booking->car?->type }}</span><span>💺 {{ $booking->car?->car_seat }} seats</span><span>📍 {{ $booking->car?->city }}</span>
                    </div>
                    <div style="margin-top:8px"><a href="{{ route('cars.show',$booking->car) }}" class="btn btn-ghost" style="font-size:12px;padding:5px 12px">View Car →</a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="trip-card">
        <div class="trip-card-head">📅 Trip Dates</div>
        <div class="trip-card-body">
            <div class="info-grid">
                <div><div class="info-label">Start</div><div class="info-val">{{ \Carbon\Carbon::parse($booking->start_date)->format('D, M d Y') }}@if($booking->start_time)<span style="color:var(--text-muted);font-weight:400"> at {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</span>@endif</div></div>
                <div><div class="info-label">End</div><div class="info-val">{{ \Carbon\Carbon::parse($booking->end_date)->format('D, M d Y') }}@if($booking->end_time)<span style="color:var(--text-muted);font-weight:400"> at {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</span>@endif</div></div>
                <div><div class="info-label">Duration</div><div class="info-val">{{ $booking->days }} day{{ $booking->days>1?'s':'' }}</div></div>
                <div><div class="info-label">Pick-up Option</div><div class="info-val">{{ ucfirst($booking->pickup_option??'Self') }}</div></div>
            </div>
        </div>
    </div>
    <div class="trip-card">
        <div class="trip-card-head">👤 Driver Details</div>
        <div class="trip-card-body">
            <div class="info-grid">
                <div><div class="info-label">Full Name</div><div class="info-val">{{ $booking->driver_first_name }} {{ $booking->driver_last_name }}</div></div>
                <div><div class="info-label">Phone</div><div class="info-val">{{ $booking->driver_phone??'—' }}</div></div>
                <div><div class="info-label">Email</div><div class="info-val">{{ $booking->driver_email??'—' }}</div></div>
                <div><div class="info-label">License Number</div><div class="info-val">{{ $booking->driver_license??'—' }}</div></div>
                <div><div class="info-label">License Expiry</div><div class="info-val">{{ $booking->driver_license_expiry?\Carbon\Carbon::parse($booking->driver_license_expiry)->format('M d, Y'):'—' }}</div></div>
                <div><div class="info-label">Rate Type</div><div class="info-val">{{ ucfirst(str_replace('_',' ',$booking->rate_type??'—')) }}</div></div>
            </div>
        </div>
    </div>
    <div class="trip-card">
        <div class="trip-card-head">💳 Payment Summary</div>
        <div class="trip-card-body">
            <div class="price-row"><span>Subtotal ({{ $booking->days }} day{{ $booking->days>1?'s':'' }})</span><strong>${{ number_format($booking->subtotal,2) }}</strong></div>
            <div class="price-row"><span>Service Fee</span><strong>${{ number_format($booking->service_fee,2) }}</strong></div>
            @if($booking->delivery_fee>0)<div class="price-row"><span>Delivery Fee</span><strong>${{ number_format($booking->delivery_fee,2) }}</strong></div>@endif
            @if($booking->rate_extra>0)<div class="price-row"><span>Extra Charge</span><strong>${{ number_format($booking->rate_extra,2) }}</strong></div>@endif
            <div class="price-total"><span>Total</span><span>${{ number_format($booking->total,2) }}</span></div>
            <div style="margin-top:14px;display:flex;gap:20px;flex-wrap:wrap">
                <div><div class="info-label">Payment Method</div><div style="font-size:13px;font-weight:600;margin-top:2px">{{ ucfirst($booking->payment_method??'—') }}</div></div>
                <div><div class="info-label">Payment Status</div><div style="font-size:13px;font-weight:600;margin-top:2px;color:{{ $booking->payment_status==='paid'?'#22c55e':'var(--text-muted)' }}">{{ ucfirst($booking->payment_status??'—') }}</div></div>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:24px">
        <a href="{{ route('trips.index') }}" class="btn btn-ghost">← Back to My Trips</a>
        @if(in_array($booking->booking_status,['pending','confirmed']))
        <form method="POST" action="{{ route('trips.cancel',$booking) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-outline" style="border-color:var(--red);color:var(--red)">✕ Cancel Booking</button>
        </form>
        @endif
    </div>
</div>
@endsection
