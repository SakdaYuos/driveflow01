# DriveFlow — Railway Deployment Guide

## 🚀 Quick Deploy to Railway

### 1. Push to GitHub
```bash
git init
git add .
git commit -m "Initial DriveFlow commit"
git remote add origin https://github.com/YOUR_USERNAME/driveflow.git
git push -u origin main
```

### 2. Create Railway Project
- Go to [railway.app](https://railway.app)
- Click **New Project → Deploy from GitHub**
- Select your repo

### 3. Add MySQL Database
- In your Railway project, click **+ New**
- Select **Database → MySQL**
- Railway will auto-inject the DB env vars

### 4. Set Environment Variables
In Railway → your app service → **Variables**, add:

| Variable | Value |
|---|---|
| `APP_NAME` | DriveFlow |
| `APP_ENV` | production |
| `APP_DEBUG` | false |
| `APP_KEY` | (generate with `php artisan key:generate --show`) |
| `APP_URL` | https://YOUR-APP.up.railway.app |
| `SESSION_DRIVER` | file |
| `CACHE_STORE` | file |

> **DB variables** (`MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`) are **auto-injected** by Railway when you add a MySQL plugin — no action needed.

### 5. Deploy
Railway will automatically run `start.sh` which:
- Generates app key (if missing)
- Creates storage symlink
- Runs migrations
- Seeds the database
- Starts the PHP server

---

## 🔑 Demo Login Credentials

| Role | Email | Password |
|---|---|---|
| Admin | admin@driveflow.kh | Admin@2025 |
| Customer | demo@driveflow.kh | Demo@2025 |

---

## 📁 Project Structure

```
driveflow/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Dashboard, Cars, Bookings, Customers, Payments
│   │   ├── Auth/           # Login, Register, Logout
│   │   └── Customer/       # Home, Cars, Booking, Payment, Trips
│   ├── Models/             # User, Car, Booking, Customer
│   └── Http/Middleware/    # Auth, RoleMiddleware, etc.
├── database/
│   ├── migrations/         # Cars, Users, Bookings, Sessions/Cache
│   └── seeders/            # Admin + demo customer + 6 sample cars
├── public/
│   ├── css/app.css         # Admin theme
│   └── css/driveflow.css   # Customer theme
├── resources/views/
│   ├── admin/              # Dashboard, Cars, Bookings, Customers, Payments
│   ├── auth/               # Login, Register
│   ├── customer/           # Home, Cars, Checkout, Trips
│   └── layouts/            # admin.blade.php, app.blade.php
├── nixpacks.toml           # Railway build config
└── start.sh                # Railway start script
```

---

## 🐛 Bugs Fixed vs Original Code

1. `User::bookings()` had wrong foreign key (`customer_id` → `user_id`)
2. `RedirectIfAuthenticated` used non-existent `role` field (→ `is_admin`)
3. Admin booking create/edit queried `customers` table (→ `users` table)
4. `CustomerController::show()` method was missing
5. `Car::getIsAvailableAttribute()` accessor was missing
6. `customer.blade.php` layout had duplicate/broken nav block
7. `BookingController::store` didn't mirror `pickup_date`/`status` for admin panel
8. `TrustProxies` now sets `$proxies = '*'` for Railway's reverse proxy
9. Vite config simplified (removed Tailwind v4 plugin — CSS served directly)
10. `nixpacks.toml` written fresh for Railway's Nix environment
