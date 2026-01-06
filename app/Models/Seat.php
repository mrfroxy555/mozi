<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seat extends Model
{
    protected $fillable = [
        'cinema_id',
        'seat_category_id',
        'row_number',
        'seat_number',
        'seat_label',
    ];

    public function cinema(): BelongsTo
    {
        return $this->belongsTo(Cinema::class);
    }

    public function seatCategory(): BelongsTo
    {
        return $this->belongsTo(SeatCategory::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function isBookedForScreening($screeningId): bool
    {
        return $this->tickets()
            ->where('screening_id', $screeningId)
            ->exists();
    }
}