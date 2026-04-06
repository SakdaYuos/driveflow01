<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'brand', 'model', 'year', 'license_plate',
        'price_per_day', 'status', 'type', 'city',
        'fuel_type', 'car_seat', 'image', 'description',
    ];

    const STATUSES     = ['Available', 'Rented', 'Maintenance'];
    const TYPES        = ['Sedan', 'SUV', 'Van'];
    const CITIES       = ['Phnom Penh', 'Siem Reap', 'Poi Pet', 'Sihanoukville'];
    const FUEL_TYPES   = ['Petrol', 'Diesel', 'Electric', 'Hybrid'];
    const SEAT_OPTIONS = ['2', '4', '5', '6', '7', '8', '9', '12', '15'];

    protected static function booted(): void
    {
        static::saving(function ($car) {
            $car->name = $car->brand . ' ' . $car->model;
        });
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // ── Admin scopes ───────────────────────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query
            ->where('status', 'Available')
            ->whereDoesntHave('bookings', function ($q) {
                $q->whereIn('booking_status', ['pending', 'confirmed', 'active']);
            });
    }

    public function scopeByCity($query, $city)
    {
        return $city ? $query->where('city', $city) : $query;
    }

    // ── Customer scopes ────────────────────────────────────────────────────

    public function scopeByBrands($query, array $brands)
    {
        return count($brands) ? $query->whereIn('brand', $brands) : $query;
    }

    public function scopeByFuels($query, array $fuels)
    {
        return count($fuels) ? $query->whereIn('fuel_type', $fuels) : $query;
    }

    public function scopeByTypes($query, array $types)
    {
        return count($types) ? $query->whereIn('type', $types) : $query;
    }

    public function scopeByPickups($query, array $opts)
    {
        return count($opts) ? $query->whereIn('pickup_option', $opts) : $query;
    }

    public function scopeBySeats($query, ?string $seats)
    {
        return match($seats) {
            '2-4'   => $query->whereBetween('car_seat', [2, 4]),
            '5'     => $query->where('car_seat', 5),
            '7+'    => $query->where('car_seat', '>=', 7),
            default => $query,
        };
    }

    public function scopeMaxPrice($query, $max)
    {
        return $max ? $query->where('price_per_day', '<=', $max) : $query;
    }

    public function scopeSorted($query, ?string $sort)
    {
        return match($sort) {
            'price_asc'  => $query->orderBy('price_per_day'),
            'price_desc' => $query->orderByDesc('price_per_day'),
            'newest'     => $query->orderByDesc('year'),
            default      => $query->orderByDesc('created_at'),
        };
    }

    // ── Accessors ──────────────────────────────────────────────────────────

    public function getPriceFormattedAttribute(): string
    {
        return '$' . number_format($this->price_per_day, 0);
    }

    public function getNameAttribute(): string
    {
        return $this->brand . ' ' . $this->model;
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->attributes['status'] === 'Available';
    }
}
