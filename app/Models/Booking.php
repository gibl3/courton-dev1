<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    // Booking Statuses
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    // Payment Statuses
    public const PAYMENT_STATUS_PENDING = 'pending';
    public const PAYMENT_STATUS_PAID = 'paid';
    public const PAYMENT_STATUS_REFUNDED = 'refunded';

    protected $fillable = [
        'user_id',
        'court_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration', // in hours
        'total_amount',
        'status', // 'pending', 'confirmed', 'cancelled', 'completed'
        'payment_status', // 'pending', 'paid', 'refunded'
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration' => 'decimal:1',
        'total_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', now())
            ->where('status', 'confirmed');
    }

    public function scopePast($query)
    {
        return $query->where('booking_date', '<', now())
            ->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    // Helper Methods
    public function isUpcoming()
    {
        return $this->booking_date >= now() && $this->status === self::STATUS_CONFIRMED;
    }

    public function isPast()
    {
        return $this->booking_date < now() && $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function canBeCancelled()
    {
        return ($this->status === self::STATUS_CONFIRMED || $this->status === self::STATUS_PENDING) &&
            now()->diffInHours($this->booking_date) >= 24;
    }
}
