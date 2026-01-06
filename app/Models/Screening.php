<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Screening extends Model
{
    protected $fillable = [
        'movie_id',
        'cinema_id',
        'start_time',
        'end_time',
        'is_visible',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_visible' => 'boolean',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function cinema(): BelongsTo
    {
        return $this->belongsTo(Cinema::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function availableSeats()
    {
        $bookedSeatIds = $this->tickets()->pluck('seat_id');
        
        return Seat::where('cinema_id', $this->cinema_id)
            ->whereNotIn('id', $bookedSeatIds)
            ->with('seatCategory')
            ->orderBy('row_number')
            ->orderBy('seat_number')
            ->get();
    }

    public function bookedSeatsCount(): int
    {
        return $this->tickets()->count();
    }

    public function availableSeatsCount(): int
    {
        return $this->cinema->capacity - $this->bookedSeatsCount();
    }
}