<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Ticket extends Model
{
    protected $fillable = [
        'booking_id',
        'seat_id',
        'screening_id',
        'qr_code',
        'price',
        'is_used',
    ];

    protected $casts = [
        'is_used' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($ticket) {
            if (empty($ticket->qr_code)) {
                $ticket->qr_code = strtoupper(Str::random(16));
            }
        });
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function screening(): BelongsTo
    {
        return $this->belongsTo(Screening::class);
    }
}