<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'avatar',
        'is_admin', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin'          => 'boolean',
        'password'          => 'hashed',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function getAvatarInitialsAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    public function scopeCustomers($query)
    {
        return $query->where('is_admin', false);
    }
}
