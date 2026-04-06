<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — DriveFlow</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>

@if(session('success'))
    <div data-flash-success="{{ session('success') }}" hidden></div>
@endif
@if(session('error'))
    <div data-flash-error="{{ session('error') }}" hidden></div>
@endif

<div class="app-layout">

    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo">Drive<span>Flow</span></div>
            <span class="brand-sub">Fleet Management</span>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">Admin</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section-label">Overview</span>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa fa-gauge-high"></i> Dashboard
            </a>

            <span class="nav-section-label">Management</span>
            <a href="{{ route('admin.cars.index') }}" class="nav-link {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}">
                <i class="fa fa-car"></i> Fleet / Cars
            </a>
            <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="fa fa-calendar-check"></i> Bookings
            </a>
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fa fa-users"></i> Customers
            </a>

            <span class="nav-section-label">Finance</span>
            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fa fa-credit-card"></i> Payments
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-full" style="font-size:12px">
                    <i class="fa fa-right-from-bracket"></i> Log Out
                </button>
            </form>
        </div>
    </aside>

    <div class="main-content">
        <div class="topbar">
            <div class="topbar-title">
                @yield('page-title', 'Dashboard')
                @hasSection('page-subtitle')
                    <span>/ @yield('page-subtitle')</span>
                @endif
            </div>
            <div class="topbar-right">
                <span style="font-size:11px; color:var(--white-4); letter-spacing:1px">
                    <i class="fa fa-location-dot" style="color:var(--red); margin-right:4px"></i>
                    PP · SR · Poi Pet · SHV
                </span>
            </div>
        </div>

        <div class="page-body">
            @yield('content')
        </div>
    </div>

</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
