<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number', 'user_id', 'car_id',
        'start_date', 'end_date', 'start_time', 'end_time', 'days',
        'pickup_option', 'rate_type',
        'driver_first_name', 'driver_last_name',
        'driver_phone', 'driver_email',
        'driver_license', 'driver_license_expiry',
        'subtotal', 'service_fee', 'delivery_fee', 'rate_extra', 'total',
        'payment_method', 'payment_status', 'booking_status',
        'card_last_four', 'card_brand', 'card_holder_name', 'card_expiry', 'transaction_id',
        'rating', 'review_text', 'notes',
        // Admin-panel fields
        'customer_id', 'pickup_date', 'return_date', 'total_price', 'status',
    ];

    protected $casts = [
        'start_date'            => 'date',
        'end_date'              => 'date',
        'pickup_date'           => 'date',
        'return_date'           => 'date',
        'driver_license_expiry' => 'date',
        'subtotal'              => 'decimal:2',
        'service_fee'           => 'decimal:2',
        'delivery_fee'          => 'decimal:2',
        'rate_extra'            => 'decimal:2',
        'total'                 => 'decimal:2',
        'total_price'           => 'decimal:2',
    ];

    const STATUSES = ['Pending', 'Confirmed', 'Ongoing', 'Completed', 'Cancelled'];

    // ── Relationships ──────────────────────────────────────────────────────

    public function user()     { return $this->belongsTo(User::class); }
    public function customer() { return $this->belongsTo(User::class, 'user_id'); }
    public function car()      { return $this->belongsTo(Car::class); }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeForUser($q, $uid)  { return $q->where('user_id', $uid); }
    public function scopeToday($q)          { return $q->whereDate('created_at', Carbon::today()); }
    public function scopeActive($q)         { return $q->whereIn('booking_status', ['confirmed', 'active']); }

    // ── Helpers ────────────────────────────────────────────────────────────

    public static function generateNumber(): string
    {
        do {
            $n = 'DF-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('booking_number', $n)->exists());
        return $n;
    }

    public function getDaysAttribute(): int
    {
        $from = $this->start_date ?? $this->pickup_date;
        $to   = $this->end_date   ?? $this->return_date;
        return $from && $to ? max(1, $from->diffInDays($to)) : ($this->attributes['days'] ?? 1);
    }

    public function getDriverFullNameAttribute(): string
    {
        return "{$this->driver_first_name} {$this->driver_last_name}";
    }

    public function getCardSummaryAttribute(): ?string
    {
        if (!$this->card_last_four) return null;
        $brand = $this->card_brand ? ucfirst($this->card_brand) . ' ' : '';
        return $brand . '•••• ' . $this->card_last_four;
    }

    public function getStatusBadgeAttribute(): array
    {
        return match($this->booking_status ?? strtolower($this->status ?? 'pending')) {
            'confirmed'  => ['label' => 'Confirmed',  'cls' => 'badge-confirmed'],
            'active'     => ['label' => 'Active',     'cls' => 'badge-ongoing'],
            'completed'  => ['label' => 'Completed',  'cls' => 'badge-completed'],
            'cancelled'  => ['label' => 'Cancelled',  'cls' => 'badge-cancelled'],
            default      => ['label' => 'Pending',    'cls' => 'badge-pending'],
        };
    }

    public function getPaymentBadgeAttribute(): array
    {
        return match($this->payment_status ?? 'pending') {
            'paid'     => ['label' => 'Paid',     'cls' => 'badge-paid'],
            'refunded' => ['label' => 'Refunded', 'cls' => 'badge-confirmed'],
            default    => ['label' => 'Unpaid',   'cls' => 'badge-unpaid'],
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status ?? ucfirst($this->booking_status ?? 'Pending')) {
            'Pending'   => 'badge-pending',
            'Confirmed' => 'badge-confirmed',
            'Ongoing'   => 'badge-ongoing',
            'Completed' => 'badge-completed',
            'Cancelled' => 'badge-cancelled',
            default     => 'badge-secondary',
        };
    }

    public function getPaymentBadgeClass(): string
    {
        return ($this->payment_status === 'paid' || $this->payment_status === 'Paid')
            ? 'badge-paid' : 'badge-unpaid';
    }

    public static function calculateTotal(Car $car, string $pickup, string $return): float
    {
        $days = max(1, Carbon::parse($pickup)->diffInDays(Carbon::parse($return)));
        return $days * $car->price_per_day;
    }
}
