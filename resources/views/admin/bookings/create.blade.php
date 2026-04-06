@extends('layouts.admin')
@section('title','New Booking')
@section('page-title','New Booking')
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2><i class="fa fa-calendar-plus" style="color:var(--red);margin-right:10px"></i>Create Booking</h2><p>Reserve a vehicle for a customer</p></div>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-light"><i class="fa fa-arrow-left"></i> Back</a>
</div>
@if($errors->any())<div class="alert alert-danger"><i class="fa fa-circle-xmark"></i><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
<form method="POST" action="{{ route('admin.bookings.store') }}">
@csrf
<div class="grid-2" style="align-items:start">
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-user"></i> Customer & Vehicle</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
                <div><label class="form-label">Customer *</label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                        <option value="">— Select Customer —</option>
                        @foreach($customers as $c)<option value="{{ $c->id }}" {{ old('customer_id')==$c->id?'selected':'' }}>{{ $c->name }} — {{ $c->phone }}</option>@endforeach
                    </select>@error('customer_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div><label class="form-label">Car (Available Only) *</label>
                    <select name="car_id" id="car_id" class="form-select @error('car_id') is-invalid @enderror">
                        <option value="">— Select Car —</option>
                        @foreach($cars as $car)<option value="{{ $car->id }}" data-price="{{ $car->price_per_day }}" {{ old('car_id')==$car->id?'selected':'' }}>{{ $car->name }} — {{ $car->city }} — ${{ number_format($car->price_per_day,0) }}/day</option>@endforeach
                    </select>@error('car_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-calendar"></i> Rental Dates</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
                <div><label class="form-label">Pickup Date *</label><input type="date" name="pickup_date" id="pickup_date" class="form-control" value="{{ old('pickup_date',date('Y-m-d')) }}" min="{{ date('Y-m-d') }}"></div>
                <div><label class="form-label">Return Date *</label><input type="date" name="return_date" id="return_date" class="form-control" value="{{ old('return_date') }}" min="{{ date('Y-m-d',strtotime('+1 day')) }}"></div>
                <div class="price-preview" id="price-preview" style="display:none"><div><div class="price-label">Estimated Total</div><div class="price-detail" id="price-detail"></div></div><div class="price-val" id="price-val">$0</div></div>
            </div>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-sliders"></i> Booking Settings</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
                <div><label class="form-label">Status</label><select name="status" class="form-select">@foreach(\App\Models\Booking::STATUSES as $s)<option value="{{ $s }}" {{ old('status','Pending')==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select></div>
                <div><label class="form-label">Payment Status</label><select name="payment_status" class="form-select"><option value="Unpaid" {{ old('payment_status')=='Unpaid'?'selected':'' }}>Unpaid</option><option value="Paid" {{ old('payment_status')=='Paid'?'selected':'' }}>Paid</option></select></div>
                <div><label class="form-label">Notes</label><textarea name="notes" class="form-control" placeholder="Optional notes…" rows="4">{{ old('notes') }}</textarea></div>
            </div>
        </div>
        <div style="display:flex;gap:10px">
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-light" style="flex:1;justify-content:center">Cancel</a>
            <button type="submit" class="btn btn-primary" style="flex:2"><i class="fa fa-save"></i> Create Booking</button>
        </div>
    </div>
</div>
</form>
@endsection
