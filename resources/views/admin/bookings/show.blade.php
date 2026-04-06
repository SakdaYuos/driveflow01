@extends('layouts.admin')
@section('title','Booking #'.$booking->id)
@section('page-title','Booking Detail')
@section('page-subtitle','#'.$booking->id)
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2><i class="fa fa-calendar-check" style="color:var(--red);margin-right:10px"></i>Booking #{{ $booking->id }}</h2><p>Created {{ $booking->created_at->format('d M Y, H:i') }}</p></div>
    <div style="display:flex;gap:8px"><a href="{{ route('admin.bookings.edit',$booking) }}" class="btn btn-primary"><i class="fa fa-pen"></i> Edit</a><a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-light"><i class="fa fa-arrow-left"></i> Back</a></div>
</div>
<div class="grid-2" style="align-items:start">
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-circle-info"></i> Booking Overview</div><span class="badge badge-{{ strtolower($booking->status ?? $booking->booking_status) }}" style="font-size:12px">{{ $booking->status ?? ucfirst($booking->booking_status) }}</span></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:16px">
                <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:12px;border-bottom:1px solid var(--border)">
                    <div>
                        <div style="font-size:11px;color:var(--white-4);letter-spacing:1px;text-transform:uppercase;margin-bottom:3px">Total Amount</div>
                        <div style="font-family:'Barlow Condensed',sans-serif;font-size:36px;font-weight:700;color:var(--red)">${{ number_format($booking->total_price ?? $booking->total ?? 0,2) }}</div>
                        <div style="font-size:12px;color:var(--white-3)">{{ $booking->days }} day(s) rental</div>
                    </div>
                    <span class="badge badge-{{ ($booking->payment_status==='Paid'||$booking->payment_status==='paid')?'paid':'unpaid' }}" style="font-size:13px;padding:6px 14px">{{ $booking->payment_status }}</span>
                </div>
                <div class="detail-row">
                    <div class="detail-item"><div class="detail-label">Pickup Date</div><div class="detail-value">{{ ($booking->pickup_date??$booking->start_date) ? \Carbon\Carbon::parse($booking->pickup_date??$booking->start_date)->format('d M Y') : '—' }}</div></div>
                    <div class="detail-item"><div class="detail-label">Return Date</div><div class="detail-value">{{ ($booking->return_date??$booking->end_date) ? \Carbon\Carbon::parse($booking->return_date??$booking->end_date)->format('d M Y') : '—' }}</div></div>
                </div>
                @if($booking->booking_number)<div style="font-size:12px;color:var(--white-4)">Booking # <strong style="color:var(--white-2)">{{ $booking->booking_number }}</strong></div>@endif
                @if($booking->notes)<div style="background:var(--black-3);border:1px solid var(--border);padding:12px;font-size:13px;color:var(--white-3)"><strong style="color:var(--white-2);display:block;margin-bottom:4px">Notes:</strong>{{ $booking->notes }}</div>@endif
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-rotate"></i> Update Status</div></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.bookings.status',$booking) }}">@csrf @method('PATCH')
                    <div style="display:flex;gap:8px"><select name="status" class="form-select" style="flex:1">@foreach(\App\Models\Booking::STATUSES as $s)<option value="{{ $s }}" {{ ($booking->status??'')==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select><button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update</button></div>
                </form>
                @if(!in_array($booking->status??'',['Completed','Cancelled']))
                <form method="POST" action="{{ route('admin.bookings.cancel',$booking) }}" style="margin-top:10px">@csrf @method('PATCH')
                    <button type="submit" class="btn btn-outline-danger btn-full" onclick="return confirm('Cancel this booking?')"><i class="fa fa-ban"></i> Cancel Booking</button>
                </form>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-credit-card"></i> Payment</div></div>
            <div class="card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px"><span style="font-size:13px;color:var(--white-3)">Payment Status:</span><span class="badge badge-{{ ($booking->payment_status==='Paid'||$booking->payment_status==='paid')?'paid':'unpaid' }}">{{ $booking->payment_status }}</span></div>
                <form method="POST" action="{{ route('admin.payments.toggle',$booking) }}">@csrf @method('PATCH')
                    <button type="submit" class="btn btn-full {{ ($booking->payment_status==='Paid'||$booking->payment_status==='paid')?'btn-outline-danger':'btn-success' }}">
                        <i class="fa fa-{{ ($booking->payment_status==='Paid'||$booking->payment_status==='paid')?'xmark':'check' }}"></i>
                        Mark as {{ ($booking->payment_status==='Paid'||$booking->payment_status==='paid')?'Unpaid':'Paid' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-user"></i> Customer</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:12px">
                <div style="font-size:18px;font-weight:700;color:var(--white)">{{ $booking->user->name ?? '—' }}</div>
                @if($booking->user)
                <div style="display:flex;flex-direction:column;gap:8px;font-size:13px;color:var(--white-3)">
                    <div><i class="fa fa-phone" style="color:var(--red);margin-right:8px"></i>{{ $booking->user->phone ?? '—' }}</div>
                    <div><i class="fa fa-envelope" style="color:var(--red);margin-right:8px"></i>{{ $booking->user->email }}</div>
                </div>
                @endif
                @if($booking->driver_first_name)
                <div style="background:var(--black-3);border:1px solid var(--border);padding:12px;font-size:13px">
                    <div style="font-size:10px;color:var(--white-4);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px">Driver Info</div>
                    <div style="color:var(--white)">{{ $booking->driver_first_name }} {{ $booking->driver_last_name }}</div>
                    <div style="color:var(--white-3)">{{ $booking->driver_phone }}</div>
                    <div style="color:var(--white-3)">{{ $booking->driver_license }}</div>
                </div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-car"></i> Vehicle</div></div>
            <div class="card-body">
                @if($booking->car)
                @if($booking->car->image)<img src="{{ asset('storage/'.$booking->car->image) }}" style="width:100%;height:140px;object-fit:cover;margin-bottom:14px;border-radius:2px">@endif
                <div style="font-size:18px;font-weight:700;color:var(--white);margin-bottom:10px">{{ $booking->car->name }}</div>
                <div style="display:flex;flex-direction:column;gap:8px;font-size:13px;color:var(--white-3)">
                    @foreach([['City',$booking->car->city],['Plate',$booking->car->license_plate],['Type',$booking->car->type],['Fuel',$booking->car->fuel_type]] as [$l,$v])
                    <div style="display:flex;justify-content:space-between"><span>{{ $l }}</span><span style="color:var(--white)">{{ $v }}</span></div>
                    @endforeach
                    <div style="display:flex;justify-content:space-between;padding-top:8px;border-top:1px solid var(--border)"><span>Price/Day</span><span style="color:var(--red);font-family:'Barlow Condensed',sans-serif;font-size:18px;font-weight:700">${{ number_format($booking->car->price_per_day,0) }}</span></div>
                </div>
                <a href="{{ route('admin.cars.show',$booking->car) }}" class="btn btn-outline-light btn-sm" style="margin-top:14px;width:100%"><i class="fa fa-arrow-up-right-from-square"></i> View Car</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
