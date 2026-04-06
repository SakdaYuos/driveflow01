@extends('layouts.admin')
@section('title','Edit Customer — '.$customer->name)
@section('page-title','Edit Customer')
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2><i class="fa fa-user-pen" style="color:var(--red);margin-right:10px"></i>Edit Customer</h2><p>{{ $customer->name }}</p></div>
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-light"><i class="fa fa-arrow-left"></i> Back</a>
</div>
<form method="POST" action="{{ route('admin.customers.update',$customer) }}">@csrf @method('PUT')
<div class="grid-2" style="align-items:start;max-width:900px">
    <div class="card">
        <div class="card-header"><div class="card-title"><i class="fa fa-user"></i> Personal Information</div></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
            <div><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name',$customer->name) }}" required></div>
            <div><label class="form-label">Phone Number *</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$customer->phone) }}" required></div>
            <div><label class="form-label">Email Address</label><input type="email" name="email" class="form-control" value="{{ old('email',$customer->email) }}"></div>
            <div><label class="form-label">New Password (leave blank to keep)</label><input type="password" name="password" class="form-control"></div>
            <div><label class="form-label">Confirm Password</label><input type="password" name="password_confirmation" class="form-control"></div>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card">
            <div class="card-header"><div class="card-title"><i class="fa fa-location-dot"></i> Location</div></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px">
                <div><label class="form-label">City</label><select name="city" class="form-select"><option value="">— Select City —</option>@foreach(\App\Models\Car::CITIES as $c)<option value="{{ $c }}" {{ old('city',$customer->city)==$c?'selected':'' }}>{{ $c }}</option>@endforeach</select></div>
                <div><label class="form-label">Address</label><textarea name="address" class="form-control" rows="3">{{ old('address',$customer->address) }}</textarea></div>
            </div>
        </div>
        <div style="display:flex;gap:10px">
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-light" style="flex:1;justify-content:center">Cancel</a>
            <button type="submit" class="btn btn-primary" style="flex:2"><i class="fa fa-save"></i> Update Customer</button>
        </div>
    </div>
</div>
</form>
@endsection
