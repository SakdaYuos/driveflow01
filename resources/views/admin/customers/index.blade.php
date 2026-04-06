@extends('layouts.admin')
@section('title','Customers')
@section('page-title','Customers')
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2><i class="fa fa-users" style="color:var(--red);margin-right:10px"></i>Customer Management</h2><p>Keep records of all renters</p></div>
    <a href="{{ route('admin.customers.create') }}" class="btn btn-primary"><i class="fa fa-user-plus"></i> Add Customer</a>
</div>
<form method="GET" action="{{ route('admin.customers.index') }}">
<div class="toolbar"><div class="toolbar-left">
    <div class="search-group"><i class="fa fa-search"></i><input type="text" name="search" class="form-control" placeholder="Search name, phone, email…" value="{{ request('search') }}" style="width:280px;padding-left:36px"></div>
    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Search</button>
    @if(request('search'))<a href="{{ route('admin.customers.index') }}" class="btn btn-outline-light btn-sm"><i class="fa fa-xmark"></i> Clear</a>@endif
</div></div>
</form>
<div class="table-wrapper">
<table><thead><tr><th>#</th><th>Name</th><th>Phone</th><th>Email</th><th>City</th><th>Bookings</th><th>Actions</th></tr></thead>
<tbody>
@forelse($customers as $customer)
<tr>
    <td style="color:var(--white-4);font-size:11px">{{ $customer->id }}</td>
    <td>
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:34px;height:34px;background:var(--red);border-radius:2px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0">{{ strtoupper(substr($customer->name,0,1)) }}</div>
            <div><div style="font-weight:600;color:var(--white)">{{ $customer->name }}</div></div>
        </div>
    </td>
    <td style="font-family:'Barlow Condensed',sans-serif;font-size:14px;color:var(--white-2)">{{ $customer->phone ?? '—' }}</td>
    <td style="color:var(--white-3);font-size:12px">{{ $customer->email ?: '—' }}</td>
    <td style="font-size:12px;color:var(--white-3)">{{ $customer->city ?? '—' }}</td>
    <td style="text-align:center"><span style="background:var(--red-glow);border:1px solid var(--border-red);color:var(--red-light);padding:2px 10px;font-size:12px;font-weight:600">{{ $customer->bookings_count ?? 0 }}</span></td>
    <td>
        <div style="display:flex;gap:5px">
            <a href="{{ route('admin.customers.show',$customer) }}" class="btn btn-outline-light btn-sm btn-icon"><i class="fa fa-eye"></i></a>
            <a href="{{ route('admin.customers.edit',$customer) }}" class="btn btn-outline-light btn-sm btn-icon"><i class="fa fa-pen"></i></a>
            <form method="POST" action="{{ route('admin.customers.destroy',$customer) }}" id="del-cust-{{ $customer->id }}">@csrf @method('DELETE')</form>
            <button data-confirm-delete="del-cust-{{ $customer->id }}" data-message="Delete {{ $customer->name }}?" class="btn btn-sm btn-icon" style="background:transparent;border:1px solid var(--border);color:var(--red);cursor:pointer"><i class="fa fa-trash"></i></button>
        </div>
    </td>
</tr>
@empty
<tr><td colspan="7"><div class="empty-state"><i class="fa fa-users"></i><h5>No customers found</h5><a href="{{ route('admin.customers.create') }}" class="btn btn-primary btn-sm mt-3"><i class="fa fa-user-plus"></i> Add Customer</a></div></td></tr>
@endforelse
</tbody></table>
</div>
<div style="margin-top:16px">{{ $customers->links() }}</div>
@endsection
