@extends('layouts.admin')
@section('title','Add Customer')
@section('page-title','Add Customer')
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2><i class="fa fa-user-plus" style="color:var(--red);margin-right:10px"></i>Add Customer</h2><p>Register a new customer account</p></div>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-light"><i class="fa fa-arrow-left"></i> Back</a>
</div>
@if($errors->any())<div class="alert alert-danger"><i class="fa fa-circle-xmark"></i><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
<form method="POST" action="{{ route('admin.customers.store') }}">@csrf
<div class="grid-2" style="align-items:start;max-width:900px">
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fa fa-user"></i> Personal Information</div></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
            <div><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>@error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
            <div><label class="form-label">Phone Number *</label><input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>@error('phone')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
            <div><label class="form-label">Email Address</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">@error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror</div>
            <div><label class="form-label">Password (leave blank for default)</label><input type="password" name="password" class="form-control" placeholder="Min 8 characters"></div>
            <div><label class="form-label">Confirm Password</label><input type="password" name="password_confirmation" class="form-control"></div>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-location-dot"></i> Location</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
                <div><label class="form-label">City</label><select name="city" class="form-select"><option value="">— Select City —</option>@foreach(\App\Models\Car::CITIES as $c)<option value="{{ $c }}" {{ old('city')==$c?'selected':'' }}>{{ $c }}</option>@endforeach</select></div>
                <div><label class="form-label">Address</label><textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea></div>
            </div>
        </div>
        <div style="display:flex;gap:10px">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-light" style="flex:1;justify-content:center">Cancel</a>
            <button type="submit" class="btn btn-primary" style="flex:2"><i class="fa fa-save"></i> Save Customer</button>
        </div>
    </div>
</div>
</form>
@endsection
