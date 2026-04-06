@extends('layouts.admin')
@section('title','Edit Car — '.$car->name)
@section('page-title','Edit Car')
@section('page-subtitle',$car->name)
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2><i class="fa fa-pen" style="color:var(--red);margin-right:10px"></i>Edit Car</h2><p>Update information for {{ $car->name }}</p></div>
    <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-light"><i class="fa fa-arrow-left"></i> Back to Fleet</a>
</div>
@if($errors->any())<div class="alert alert-danger"><i class="fa fa-circle-xmark"></i><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
<form method="POST" action="{{ route('admin.cars.update',$car) }}" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="grid-2" style="align-items:start">
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-info-circle"></i> Basic Information</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div><label class="form-label">Brand *</label><input type="text" name="brand" class="form-control" value="{{ old('brand',$car->brand) }}"></div>
                    <div><label class="form-label">Model *</label><input type="text" name="model" class="form-control" value="{{ old('model',$car->model) }}"></div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div><label class="form-label">Year *</label><input type="number" name="year" class="form-control" value="{{ old('year',$car->year) }}" min="1990" max="2030"></div>
                    <div><label class="form-label">License Plate *</label><input type="text" name="license_plate" class="form-control" value="{{ old('license_plate',$car->license_plate) }}"></div>
                </div>
                <div><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description',$car->description) }}</textarea></div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-image"></i> Car Photo</div></div>
            <div class="card-body">
                <div class="car-image-wrap" style="margin-bottom:14px">
                    @if($car->image)<img src="{{ asset('storage/'.$car->image) }}" id="image-preview"><span id="image-placeholder" style="display:none"><i class="fa fa-car placeholder-icon"></i></span>
                    @else<img src="" id="image-preview" style="display:none"><span id="image-placeholder"><i class="fa fa-car placeholder-icon"></i></span>@endif
                </div>
                <input type="file" name="image" id="image" accept="image/*">
                <p style="font-size:11px;color:var(--white-4);margin-top:8px">Upload new photo to replace current. JPG, PNG — max 2MB</p>
            </div>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-sliders"></i> Settings</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
                <div><label class="form-label">Vehicle Type *</label><select name="type" class="form-select">@foreach(\App\Models\Car::TYPES as $t)<option value="{{ $t }}" {{ old('type',$car->type)==$t?'selected':'' }}>{{ $t }}</option>@endforeach</select></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                    <div><label class="form-label">Fuel Type *</label><select name="fuel_type" class="form-select"><option value="">— Select —</option>@foreach(\App\Models\Car::FUEL_TYPES as $f)<option value="{{ $f }}" {{ old('fuel_type',$car->fuel_type)==$f?'selected':'' }}>{{ $f }}</option>@endforeach</select></div>
                    <div><label class="form-label">Car Seats *</label><select name="car_seat" class="form-select"><option value="">— Select —</option>@foreach(\App\Models\Car::SEAT_OPTIONS as $s)<option value="{{ $s }}" {{ old('car_seat',$car->car_seat)==$s?'selected':'' }}>{{ $s }} Seats</option>@endforeach</select></div>
                </div>
                <div><label class="form-label">City *</label><select name="city" class="form-select">@foreach(\App\Models\Car::CITIES as $c)<option value="{{ $c }}" {{ old('city',$car->city)==$c?'selected':'' }}>{{ $c }}</option>@endforeach</select></div>
                <div><label class="form-label">Status *</label><select name="status" class="form-select">@foreach(\App\Models\Car::STATUSES as $s)<option value="{{ $s }}" {{ old('status',$car->status)==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select></div>
                <div><label class="form-label">Price Per Day (USD) *</label><div class="input-group"><span class="input-group-text"><i class="fa fa-dollar-sign"></i></span><input type="number" name="price_per_day" class="form-control" value="{{ old('price_per_day',$car->price_per_day) }}" min="1" step="0.01"></div></div>
            </div>
        </div>
        <div style="display:flex;gap:10px">
            <a href="{{ route('admin.cars.index') }}" class="btn btn-outline-light" style="flex:1;justify-content:center">Cancel</a>
            <button type="submit" class="btn btn-primary" style="flex:2"><i class="fa fa-save"></i> Update Car</button>
        </div>
    </div>
</div>
</form>
@endsection
