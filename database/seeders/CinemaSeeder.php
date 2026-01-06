<?php

namespace Database\Seeders;

use App\Models\Cinema;
use Illuminate\Database\Seeder;

class CinemaSeeder extends Seeder
{
    public function run(): void
    {
        $cinemas = [
            [
                'name' => 'Nagy terem',
                'capacity' => 120,
                'rows' => 12,
                'seats_per_row' => 10,
            ],
            [
                'name' => 'Közepes terem',
                'capacity' => 60,
                'rows' => 10,
                'seats_per_row' => 6,
            ],
            [
                'name' => 'Kisterem',
                'capacity' => 20,
                'rows' => 5,
                'seats_per_row' => 4,
            ],
        ];

        foreach ($cinemas as $cinema) {
            Cinema::firstOrCreate(['name' => $cinema['name']], $cinema);
        }
    }
}