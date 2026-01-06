<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration',
        'genre',
        'director',
        'age_rating',
        'poster_url',
        'trailer_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function screenings(): HasMany
    {
        return $this->hasMany(Screening::class);
    }

    public function activeScreenings()
    {
        return $this->screenings()
            ->where('is_visible', true)
            ->where('start_time', '>', now())
            ->orderBy('start_time');
    }
}