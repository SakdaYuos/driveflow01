@extends('layouts.admin')
@section('title','Fleet Management')
@section('page-title','Fleet')
@section('page-subtitle','Cars')
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2><i class="fa fa-car" style="color:var(--red);margin-right:10px"></i>Fleet Management</h2><p>Manage all vehicles across 4 cities</p></div>
    <a href="{{ route('admin.cars.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Car</a>
</div>
<form method="GET" action="{{ route('admin.cars.index') }}">
<div class="toolbar"><div class="toolbar-left">
    <div class="search-group"><i class="fa fa-search"></i><input type="text" name="search" class="form-control" placeholder="Search name, brand, plate…" value="{{ request('search') }}" style="width:240px;padding-left:36px"></div>
    <select name="status" class="form-select" style="width:140px"><option value="">All Status</option>@foreach(\App\Models\Car::STATUSES as $s)<option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ $s }}</option>@endforeach</select>
    <select name="city" class="form-select" style="width:150px"><option value="">All Cities</option>@foreach(\App\Models\Car::CITIES as $c)<option value="{{ $c }}" {{ request('city')==$c?'selected':'' }}>{{ $c }}</option>@endforeach</select>
    <select name="type" class="form-select" style="width:120px"><option value="">All Types</option>@foreach(\App\Models\Car::TYPES as $t)<option value="{{ $t }}" {{ request('type')==$t?'selected':'' }}>{{ $t }}</option>@endforeach</select>
    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter</button>
    @if(request()->hasAny(['search','status','city','type']))<a href="{{ route('admin.cars.index') }}" class="btn btn-outline-light btn-sm"><i class="fa fa-xmark"></i> Clear</a>@endif
</div></div>
</form>
<div class="table-wrapper">
<table><thead><tr><th>#</th><th>Image</th><th>Brand / Model</th><th>Year</th><th>Type</th><th>Fuel</th><th>Seats</th><th>Plate</th><th>City</th><th>Price/Day</th><th>Status</th><th>Actions</th></tr></thead>
<tbody>
@forelse($cars as $car)
<tr>
    <td style="color:var(--white-4);font-size:11px">{{ $car->id }}</td>
    <td>
        @if($car->image)
            <img src="{{ asset('storage/'.$car->image) }}" style="width:52px;height:38px;object-fit:cover;border-radius:2px;border:1px solid var(--border)">
        @else
            <div style="width:52px;height:38px;background:var(--black-3);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;border-radius:2px"><i class="fa fa-car" style="color:var(--white-4)"></i></div>
        @endif
    </td>
    <td style="color:var(--white-3)">{{ $car->brand }} {{ $car->model }}</td>
    <td style="color:var(--white-3)">{{ $car->year }}</td>
    <td><span class="badge badge-{{ strtolower($car->type) }}">{{ $car->type }}</span></td>
    <td style="color:var(--white-3);font-size:12px">{{ $car->fuel_type ?? '—' }}</td>
    <td style="color:var(--white-3);font-size:12px;text-align:center">{{ $car->car_seat ?? '—' }}</td>
    <td><span style="font-family:'Barlow Condensed',sans-serif;font-size:13px;color:var(--white-2);background:var(--black-3);padding:3px 8px;border:1px solid var(--border)">{{ $car->license_plate }}</span></td>
    <td style="color:var(--white-3);font-size:12px">{{ $car->city }}</td>
    <td><span style="font-family:'Barlow Condensed',sans-serif;font-size:17px;font-weight:700;color:var(--red)">${{ number_format($car->price_per_day,0) }}</span><span style="font-size:10px;color:var(--white-4)">/day</span></td>
    <td>
        <form method="POST" action="{{ route('admin.cars.status',$car) }}">@csrf @method('PATCH')
            <select name="status" class="status-select" onchange="this.form.submit()">
                @foreach(\App\Models\Car::STATUSES as $s)<option value="{{ $s }}" {{ $car->status==$s?'selected':'' }}>{{ $s }}</option>@endforeach
            </select>
        </form>
    </td>
    <td>
        <div style="display:flex;gap:5px">
            <a href="{{ route('admin.cars.show',$car) }}" class="btn btn-outline-light btn-sm btn-icon"><i class="fa fa-eye"></i></a>
            <a href="{{ route('admin.cars.edit',$car) }}" class="btn btn-outline-light btn-sm btn-icon"><i class="fa fa-pen"></i></a>
            <form method="POST" action="{{ route('admin.cars.destroy',$car) }}" id="del-car-{{ $car->id }}">@csrf @method('DELETE')</form>
            <button data-confirm-delete="del-car-{{ $car->id }}" data-message="Delete {{ $car->name }}?" class="btn btn-sm btn-icon" style="background:transparent;border:1px solid var(--border);color:var(--red);cursor:pointer"><i class="fa fa-trash"></i></button>
        </div>
    </td>
</tr>
@empty
<tr><td colspan="12"><div class="empty-state"><i class="fa fa-car"></i><h5>No cars found</h5><a href="{{ route('admin.cars.create') }}" class="btn btn-primary btn-sm mt-3"><i class="fa fa-plus"></i> Add Car</a></div></td></tr>
@endforelse
</tbody></table>
</div>
<div style="margin-top:16px">{{ $cars->links() }}</div>
@endsection
