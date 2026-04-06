@extends('layouts.app')
@section('title','My Trips')
@section('content')
<div style="max-width:900px;margin:0 auto;padding:32px 24px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px">
        <div><h1 style="font-family:'Barlow Condensed',sans-serif;font-size:36px;font-weight:800">MY TRIPS</h1><p style="color:var(--text-muted);font-size:13px">All your DriveFlow bookings</p></div>
        <a href="{{ route('cars.index') }}" class="btn btn-primary">+ Book Another Car</a>
    </div>
    @if($bookings->isEmpty())
        <div class="empty-state"><div style="font-size:56px;margin-bottom:16px">🚗</div><div style="font-size:18px;font-weight:600;margin-bottom:8px">No trips yet</div><p style="color:var(--text-muted);font-size:13px;margin-bottom:16px">Your bookings will appear here once you rent a car.</p><a href="{{ route('cars.index') }}" class="btn btn-primary">Browse Cars</a></div>
    @else
        <div style="display:flex;flex-direction:column;gap:14px">
            @foreach($bookings as $booking)
            <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden;transition:var(--transition)" onmouseover="this.style.borderColor='var(--red)'" onmouseout="this.style.borderColor='var(--border)'">
                <div style="padding:16px 18px;display:flex;align-items:center;gap:16px;flex-wrap:wrap">
                    <div style="font-size:36px">🚗</div>
                    <div style="flex:1">
                        <div style="font-family:'Barlow Condensed',sans-serif;font-size:19px;font-weight:700">{{ $booking->car->brand ?? '' }} {{ $booking->car->model ?? '' }} {{ $booking->car->year ?? '' }}</div>
                        <div style="font-size:12px;color:var(--text-muted);margin-top:2px">
                            📅 {{ $booking->start_date?$booking->start_date->format('M d'):'' }} – {{ $booking->end_date?$booking->end_date->format('M d, Y'):'' }}
                            &nbsp;·&nbsp; {{ $booking->days }} day{{ $booking->days>1?'s':'' }}
                            &nbsp;·&nbsp; 📍 {{ $booking->car->city ?? '' }}
                        </div>
                    </div>
                    <div style="text-align:right">
                        <div style="font-family:'Barlow Condensed',sans-serif;font-size:20px;font-weight:700;color:var(--red)">${{ number_format($booking->total,0) }}</div>
                        <div style="display:flex;gap:6px;margin-top:4px;justify-content:flex-end">
                            <span class="badge {{ $booking->status_badge['cls'] }}">{{ $booking->status_badge['label'] }}</span>
                            <span class="badge {{ $booking->payment_badge['cls'] }}">{{ $booking->payment_badge['label'] }}</span>
                        </div>
                    </div>
                </div>
                <div style="padding:10px 18px;border-top:1px solid var(--border);background:var(--bg-card2);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px">
                    <div style="font-size:12px;color:var(--text-muted)"># {{ $booking->booking_number }} &nbsp;·&nbsp; {{ ucfirst(str_replace('_',' ',$booking->rate_type)) }} &nbsp;·&nbsp; {{ strtoupper($booking->payment_method) }}</div>
                    <div style="display:flex;gap:8px">
                        <a href="{{ route('trips.show',$booking) }}" class="btn btn-ghost" style="padding:6px 14px;font-size:12px">View Details</a>
                        @if(in_array($booking->booking_status,['pending','confirmed']))
                        <form method="POST" action="{{ route('trips.cancel',$booking) }}" onsubmit="return confirm('Cancel this booking?')">
                            @csrf
                            <button type="submit" class="btn btn-ghost" style="padding:6px 14px;font-size:12px;color:var(--red);border-color:rgba(220,38,38,.3)">Cancel</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div style="margin-top:24px">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
