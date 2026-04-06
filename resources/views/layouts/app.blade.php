<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<title>@yield('title','DriveFlow') — Fleet Management</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@400;600;700;800&display=swap"/>
<link rel="stylesheet" href="{{ asset('css/driveflow.css') }}"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
@stack('styles')
</head>
<body>

<nav class="navbar">
    <div class="navbar-brand">
        <a href="{{ route('home') }}" class="brand-link">
            <div class="brand-logo">DRIVE<span>FLOW</span></div>
            <div class="brand-tag">Fleet Management</div>
        </a>
    </div>

    <div class="nav-links">
        <a href="{{ route('home') }}"       class="nav-link {{ request()->routeIs('home')   ? 'active':'' }}">Home</a>
        <a href="{{ route('cars.index') }}" class="nav-link {{ request()->routeIs('cars.*') ? 'active':'' }}">Browse Cars</a>

        @auth
            <a href="{{ route('trips.index') }}" class="nav-link {{ request()->routeIs('trips.*') ? 'active':'' }}">My Trips</a>
            <div class="nav-divider"></div>
            <div class="nav-profile">
                <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span class="nav-username">{{ auth()->user()->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa fa-sign-out-alt"></i> Log Out
                </button>
            </form>
        @else
            <a href="{{ route('login') }}"    class="btn btn-outline" style="padding:7px 16px;font-size:12px">Sign In</a>
            <a href="{{ route('register') }}" class="btn btn-primary" style="padding:7px 16px;font-size:12px">Register</a>
        @endauth
    </div>
</nav>

@if(session('success'))
    <div class="flash flash-success"><i class="fa fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash flash-error"><i class="fa fa-exclamation-circle"></i> {{ session('error') }}</div>
@endif
@if($errors->any())
    <div class="flash flash-error"><i class="fa fa-exclamation-circle"></i> {{ $errors->first() }}</div>
@endif

@yield('content')

<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="brand-logo" style="font-size:20px;margin-bottom:10px">DRIVE<span>FLOW</span></div>
                <p class="text-muted text-sm" style="max-width:240px;line-height:1.6">
                    Cambodia's most trusted fleet rental platform. Drive with confidence.
                </p>
            </div>
            <div>
                <div class="footer-heading">Navigate</div>
                <a href="{{ route('home') }}"       class="footer-link">Home</a>
                <a href="{{ route('cars.index') }}" class="footer-link">Browse Cars</a>
                @auth
                    <a href="{{ route('trips.index') }}" class="footer-link">My Trips</a>
                @endauth
            </div>
            <div>
                <div class="footer-heading">Cities</div>
                @foreach(['Phnom Penh','Siem Reap','Poi Pet','Sihanoukville'] as $fc)
                    <a href="{{ route('cars.index',['city'=>$fc]) }}" class="footer-link">📍 {{ $fc }}</a>
                @endforeach
            </div>
            <div>
                <div class="footer-heading">Contact</div>
                <div class="footer-link">📞 +855 23 456 789</div>
                <div class="footer-link">✉️ hello@driveflow.com</div>
                <div class="footer-link">24/7 Support</div>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} DriveFlow Fleet Management. All rights reserved.</span>
            <div style="display:flex;gap:16px">
                <span style="cursor:pointer">Privacy Policy</span>
                <span style="cursor:pointer">Terms of Service</span>
            </div>
        </div>
    </div>
</footer>

<style>
.nav-divider { width:1px;height:20px;background:rgba(255,255,255,0.15);margin:0 4px; }
.nav-profile { display:flex;align-items:center;gap:8px;padding:4px 8px; }
.nav-avatar { width:30px;height:30px;border-radius:50%;background:var(--red,#e63946);color:#fff;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
.nav-username { font-size:13px;font-weight:500;color:var(--white,#fff);max-width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }
.btn-logout { display:flex;align-items:center;gap:6px;padding:6px 14px;background:rgba(230,57,70,0.12);color:var(--red,#e63946);border:1px solid rgba(230,57,70,0.3);border-radius:7px;font-size:12px;font-weight:500;cursor:pointer;transition:background 0.2s,border-color 0.2s;white-space:nowrap; }
.btn-logout:hover { background:rgba(230,57,70,0.22);border-color:rgba(230,57,70,0.6); }
</style>

@stack('scripts')
</body>
</html>
