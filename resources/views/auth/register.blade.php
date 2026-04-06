<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — DriveFlow</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="auth-wrapper">
    <div style="position:absolute;left:0;top:0;width:3px;height:100%;background:var(--red);opacity:0.6"></div>
    <div class="auth-card" style="max-width:480px">
        <div class="auth-logo">
            <div class="logo-text">Drive<span>Flow</span></div>
            <span class="logo-sub">Create Customer Account</span>
        </div>
        <div class="auth-title">
            <i class="fa fa-user-plus" style="color:var(--red);margin-right:8px;font-size:14px"></i>
            Customer Registration
        </div>
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fa fa-circle-xmark"></i>
                <div>@foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach</div>
            </div>
        @endif
        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
                <div>
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Sokha Chenda" required>
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+855 12 345 678">
                </div>
            </div>
            <div style="margin-bottom:16px">
                <label class="form-label">Email Address *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="your@email.com" required>
                </div>
            </div>
            <div style="margin-bottom:16px">
                <label class="form-label">Password *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required>
                </div>
            </div>
            <div style="margin-bottom:24px">
                <label class="form-label">Confirm Password *</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-full" style="font-size:14px;padding:12px">
                <i class="fa fa-user-plus"></i> Create Account
            </button>
        </form>
        <div style="text-align:center;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
            <span style="font-size:13px;color:var(--white-4)">Already have an account?</span>
            <a href="{{ route('login') }}" style="color:var(--red-light);font-weight:600;font-size:13px;margin-left:6px">Sign In</a>
        </div>
    </div>
</div>
</body>
</html>
