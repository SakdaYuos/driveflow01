@extends('layouts.admin')
@section('title','Edit Booking #'.$booking->id)
@section('page-title','Edit Booking')
@section('page-subtitle','#'.$booking->id)
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2><i class="fa fa-pen" style="color:var(--red);margin-right:10px"></i>Edit Booking #{{ $booking->id }}</h2><p>{{ $booking->user->name ?? '' }} — {{ $booking->car->name ?? '' }}</p></div>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-light"><i class="fa fa-arrow-left"></i> Back</a>
</div>
@if($errors->any())<div class="alert alert-danger"><i class="fa fa-circle-xmark"></i><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
<form method="POST" action="{{ route('admin.bookings.update',$booking) }}">@csrf @method('PUT')
<div class="grid-2" style="align-items:start">
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-user"></i> Customer & Vehicle</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
                <div><label class="form-label">Customer *</label>
                    <select name="customer_id" class="form-select">
                        @foreach($customers as $c)<option value="{{ $c->id }}" {{ old('customer_id',$booking->user_id)==$c->id?'selected':'' }}>{{ $c->name }} — {{ $c->phone }}</option>@endforeach
                    </select>
                </div>
                <div><label class="form-label">Car *</label>
                    <select name="car_id" id="car_id" class="form-select">
                        @foreach($cars as $car)<option value="{{ $car->id }}" data-price="{{ $car->price_per_day }}" {{ old('car_id',$booking->car_id)==$car->id?'selected':'' }}>{{ $car->name }} — {{ $car->city }} — ${{ number_format($car->price_per_day,0) }}/day ({{ $car->status }})</option>@endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-calendar"></i> Rental Dates</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
                <div><label class="form-label">Pickup Date *</label><input type="date" name="pickup_date" id="pickup_date" class="form-control" value="{{ old('pickup_date', optional($booking->pickup_date ?? $booking->start_date)->format('Y-m-d')) }}"></div>
                <div><label class="form-label">Return Date *</label><input type="date" name="return_date" id="return_date" class="form-control" value="{{ old('return_date', optional($booking->return_date ?? $booking->end_date)->format('Y-m-d')) }}"></div>
                <div class="price-preview" id="price-preview">
                    <div><div class="price-label">Current Total</div><div class="price-detail" id="price-detail">{{ $booking->days }} day(s) × ${{ number_format($booking->car->price_per_day ?? 0,0) }}/day</div></div>
                    <div class="price-val" id="price-val">${{ number_format($booking->total_price ?? $booking->total ?? 0,0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-sliders"></i> Booking Settings</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
                <div><label class="form-label">Status</label><select name="status" class="form-select">@foreach(\App\Models\Booking::STATUSES as $s)<option value="{{ $s }}" {{ old('status',$booking->status)==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select></div>
                <div><label class="form-label">Payment Status</label><select name="payment_status" class="form-select"><option value="Unpaid" {{ old('payment_status',$booking->payment_status)=='Unpaid'?'selected':'' }}>Unpaid</option><option value="Paid" {{ old('payment_status',$booking->payment_status)=='Paid'?'selected':'' }}>Paid</option></select></div>
                <div><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="4">{{ old('notes',$booking->notes) }}</textarea></div>
            </div>
        </div>
        <div style="display:flex;gap:10px">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-light" style="flex:1;justify-content:center">Cancel</a>
            <button type="submit" class="btn btn-primary" style="flex:2"><i class="fa fa-save"></i> Update Booking</button>
        </div>
    </div>
</div>
</form>
@endsection
