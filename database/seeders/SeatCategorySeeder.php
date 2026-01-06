<?php

namespace Database\Seeders;

use App\Models\SeatCategory;
use Illuminate\Database\Seeder;

class SeatCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Normál', 'price' => 2500, 'color' => '#3b82f6'],
            ['name' => 'VIP', 'price' => 3500, 'color' => '#f59e0b'],
            ['name' => 'Diák', 'price' => 1800, 'color' => '#10b981'],
        ];

        foreach ($categories as $category) {
            SeatCategory::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}