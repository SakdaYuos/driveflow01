<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — DriveFlow</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="auth-wrapper">
    <div style="position:absolute;left:0;top:0;width:3px;height:100%;background:var(--red);opacity:0.6"></div>

    <div class="auth-card">
        <div class="auth-logo">
            <div class="logo-text">Drive<span>Flow</span></div>
            <span class="logo-sub">Car Rental — Cambodia</span>
        </div>

        <div class="auth-title">
            <i class="fa fa-lock" style="color:var(--red); margin-right:8px; font-size:14px"></i>
            Sign In to Your Account
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fa fa-circle-xmark"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div style="margin-bottom:16px">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="your@email.com" required autofocus>
                </div>
            </div>

            <div style="margin-bottom:20px">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:var(--white-3)">
                    <input type="checkbox" name="remember" style="accent-color:var(--red)">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-full" style="font-size:14px;padding:12px">
                <i class="fa fa-right-to-bracket"></i> Sign In
            </button>
        </form>


        <div style="text-align:center;margin-top:20px;padding-top:16px;border-top:1px solid var(--border);">
            <span style="font-size:13px;color:var(--white-4)">Don't have an account?</span>
            <a href="{{ route('register') }}" style="color:var(--red-light);font-weight:600;font-size:13px;margin-left:6px">
                Register as Customer
            </a>
        </div>
    </div>
</div>

</body>
</html>
