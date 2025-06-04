<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Court extends Model
{
    use HasFactory;

    // Court Types
    public const TYPE_PROFESSIONAL = 'professional';
    public const TYPE_STANDARD = 'standard';
    public const TYPE_TRAINING = 'training';

    protected $fillable = [
        'name',
        'type', // 'professional', 'standard', 'training'
        'description',
        'image_path',
        'status', // 'available', 'unavailable', 'maintenance'
        'rate_per_hour',
        'weekend_rate_per_hour',
        'opening_time',
        'closing_time'
    ];

    protected $casts = [
        'rate_per_hour' => 'decimal:2',
        'weekend_rate_per_hour' => 'decimal:2',
        'opening_time' => 'datetime',
        'closing_time' => 'datetime'
    ];

    // Accessors
    public function getOpeningTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function getClosingTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    // Additional accessors for formatted times
    public function getFormattedOpeningTimeAttribute()
    {
        return $this->opening_time ? Carbon::parse($this->opening_time)->format('g:i A') : null;
    }

    public function getFormattedClosingTimeAttribute()
    {
        return $this->closing_time ? Carbon::parse($this->closing_time)->format('g:i A') : null;
    }

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getRateForDate($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $isWeekend = $date->isWeekend();

        return [
            'amount' => $isWeekend ? $this->weekend_rate_per_hour : $this->rate_per_hour,
            'is_weekend' => $isWeekend,
            'is_wholeday' => $isWeekend
        ];
    }
}
