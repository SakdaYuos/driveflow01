@extends('layouts.app')
@section('title','Booking Confirmed')
@section('content')
<div style="max-width:500px;margin:60px auto;padding:0 24px;text-align:center">
    <div style="font-size:72px;margin-bottom:20px">🎉</div>
    <h1 style="font-family:'Barlow Condensed',sans-serif;font-size:40px;font-weight:800;margin-bottom:8px">BOOKING CONFIRMED!</h1>
    <p style="color:var(--text-muted);font-size:15px;margin-bottom:24px;line-height:1.6">Your vehicle has been reserved. We'll contact you shortly with pick-up details.</p>
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;padding:24px;margin-bottom:24px">
        <div style="font-size:12px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">Booking Number</div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-size:28px;font-weight:800;color:var(--red);letter-spacing:2px">{{ $booking->booking_number }}</div>
        <div style="height:1px;background:var(--border);margin:16px 0"></div>
        <div style="display:flex;gap:10px;align-items:center;margin-bottom:10px">
            <div style="font-size:28px">🚗</div>
            <div>
                <div style="font-weight:700">{{ $booking->car->brand }} {{ $booking->car->model }} {{ $booking->car->year }}</div>
                <div style="font-size:13px;color:var(--text-muted)">📍 {{ $booking->car->city }}</div>
            </div>
        </div>
        <div style="background:var(--bg-input);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px;font-size:13px;color:var(--text-muted)">
            <div>📅 {{ $booking->start_date->format('M d') }} – {{ $booking->end_date->format('M d, Y') }}</div>
            <div style="margin-top:4px">💰 Total: <strong style="color:var(--text)">${{ number_format($booking->total,0) }}</strong></div>
        </div>
    </div>
    <a href="{{ route('trips.index') }}" class="btn btn-primary btn-full btn-lg" style="margin-bottom:10px">View My Trips</a>
    <a href="{{ route('home') }}" class="btn btn-ghost btn-full">Back to Home</a>
</div>
@endsection
