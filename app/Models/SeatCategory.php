<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatCategory extends Model
{
    protected $fillable = [
        'name',
        'price',
        'color',
    ];

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}