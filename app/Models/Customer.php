<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'phone', 'email',
        'driver_license', 'address', 'city',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function activeBookings()
    {
        return $this->bookings()->whereIn('status', ['Confirmed', 'Ongoing']);
    }
}
