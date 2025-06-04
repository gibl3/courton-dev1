<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

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
    public const PAYMENT_STATUS_CANCELLED = 'cancelled';
    public const PAYMENT_STATUS_PENDING_REFUND = 'pending_refund';

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
        'image_path',
        'cancellation_reason'
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

    public function canBeDeleted()
    {
        // Can only delete if:
        // 1. Booking is not completed
        // 2. Payment is not paid or refunded
        // 3. Booking is not in the past
        return $this->status !== self::STATUS_COMPLETED &&
            !in_array($this->payment_status, [self::PAYMENT_STATUS_PAID, self::PAYMENT_STATUS_REFUNDED]) &&
            $this->booking_date >= now();
    }
}
