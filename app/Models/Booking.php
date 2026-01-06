<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'screening_id',
        'guest_name',
        'guest_email',
        'booking_code',
        'total_price',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = strtoupper(Str::random(8));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function screening(): BelongsTo
    {
        return $this->belongsTo(Screening::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function isGuest(): bool
    {
        return is_null($this->user_id);
    }

    public function getCustomerName(): string
    {
        return $this->isGuest() ? $this->guest_name : $this->user->name;
    }

    public function getCustomerEmail(): string
    {
        return $this->isGuest() ? $this->guest_email : $this->user->email;
    }
}