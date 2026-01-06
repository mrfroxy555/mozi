<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\Seat;
use App\Models\SeatCategory;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        $cinemas = Cinema::all();
        $normal = SeatCategory::where('name', 'Normál')->first();
        $vip = SeatCategory::where('name', 'VIP')->first();
        $student = SeatCategory::where('name', 'Diák')->first();

        foreach ($cinemas as $cinema) {
            for ($row = 1; $row <= $cinema->rows; $row++) {
                for ($seat = 1; $seat <= $cinema->seats_per_row; $seat++) {
                    $rowLetter = chr(64 + $row); // A, B, C, stb.
                    
                    // Árkategória meghatározása terem és sor alapján
                    $categoryId = $this->determineSeatCategory(
                        $cinema->name, 
                        $row, 
                        $cinema->rows,
                        $normal->id,
                        $vip->id,
                        $student->id
                    );

                    Seat::firstOrCreate([
                        'cinema_id' => $cinema->id,
                        'row_number' => $row,
                        'seat_number' => $seat,
                    ], [
                        'seat_category_id' => $categoryId,
                        'seat_label' => $rowLetter . $seat,
                    ]);
                }
            }
        }
    }

    private function determineSeatCategory($cinemaName, $row, $totalRows, $normalId, $vipId, $studentId)
    {
        // Nagy terem: hátsó sorok VIP, első sorok diák, középső normál
        if ($cinemaName === 'Nagy terem') {
            if ($row >= $totalRows - 2) return $vipId; // Utolsó 3 sor VIP
            if ($row <= 3) return $studentId; // Első 3 sor diák
            return $normalId;
        }

        // Közepes terem: hátsó sorok VIP, többi normál
        if ($cinemaName === 'Közepes terem') {
            if ($row >= $totalRows - 2) return $vipId;
            return $normalId;
        }

        // Kisterem: minden normál
        return $normalId;
    }
}