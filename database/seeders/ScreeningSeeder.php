<?php

namespace Database\Seeders;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Screening;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScreeningSeeder extends Seeder
{
    public function run(): void
    {
        $movies = Movie::all();
        $cinemas = Cinema::all();

        if ($movies->isEmpty() || $cinemas->isEmpty()) {
            $this->command->error('Nincs film vagy terem az adatbázisban!');
            return;
        }

        $today = Carbon::today();
        $screeningsData = [];

        // Ma (ma + mai vetítések)
        $screeningsData = array_merge($screeningsData, [
            // Nagy terem - ma
            ['movie' => 'Oppenheimer', 'cinema' => 'Nagy terem', 'date' => $today, 'time' => '14:00'],
            ['movie' => 'Dűne: Második rész', 'cinema' => 'Nagy terem', 'date' => $today, 'time' => '17:30'],
            ['movie' => 'Killers of the Flower Moon', 'cinema' => 'Nagy terem', 'date' => $today, 'time' => '21:00'],
            
            // Közepes terem - ma
            ['movie' => 'Barbie', 'cinema' => 'Közepes terem', 'date' => $today, 'time' => '15:00'],
            ['movie' => 'Wonka', 'cinema' => 'Közepes terem', 'date' => $today, 'time' => '17:30'],
            ['movie' => 'The Holdovers', 'cinema' => 'Közepes terem', 'date' => $today, 'time' => '20:00'],
            
            // Kisterem - ma
            ['movie' => 'A vadon hangja', 'cinema' => 'Kisterem', 'date' => $today, 'time' => '16:00'],
            ['movie' => 'Kung Fu Panda 4', 'cinema' => 'Kisterem', 'date' => $today, 'time' => '18:00'],
        ]);

        // Holnap
        $tomorrow = $today->copy()->addDay();
        $screeningsData = array_merge($screeningsData, [
            ['movie' => 'Dűne: Második rész', 'cinema' => 'Nagy terem', 'date' => $tomorrow, 'time' => '14:00'],
            ['movie' => 'Oppenheimer', 'cinema' => 'Nagy terem', 'date' => $tomorrow, 'time' => '17:30'],
            ['movie' => 'The Zone of Interest', 'cinema' => 'Nagy terem', 'date' => $tomorrow, 'time' => '21:00'],
            
            ['movie' => 'Poor Things', 'cinema' => 'Közepes terem', 'date' => $tomorrow, 'time' => '15:30'],
            ['movie' => 'Barbie', 'cinema' => 'Közepes terem', 'date' => $tomorrow, 'time' => '18:00'],
            ['movie' => 'The Holdovers', 'cinema' => 'Közepes terem', 'date' => $tomorrow, 'time' => '20:30'],
            
            ['movie' => 'Wonka', 'cinema' => 'Kisterem', 'date' => $tomorrow, 'time' => '15:00'],
            ['movie' => 'Kung Fu Panda 4', 'cinema' => 'Kisterem', 'date' => $tomorrow, 'time' => '17:00'],
        ]);

        // Holnapután
        $dayAfter = $tomorrow->copy()->addDay();
        $screeningsData = array_merge($screeningsData, [
            ['movie' => 'Killers of the Flower Moon', 'cinema' => 'Nagy terem', 'date' => $dayAfter, 'time' => '14:00'],
            ['movie' => 'Dűne', 'cinema' => 'Nagy terem', 'date' => $dayAfter, 'time' => '17:30'],
            ['movie' => 'Oppenheimer', 'cinema' => 'Nagy terem', 'date' => $dayAfter, 'time' => '21:00'],
            
            ['movie' => 'The Holdovers', 'cinema' => 'Közepes terem', 'date' => $dayAfter, 'time' => '16:00'],
            ['movie' => 'Poor Things', 'cinema' => 'Közepes terem', 'date' => $dayAfter, 'time' => '18:30'],
            ['movie' => 'A Ház', 'cinema' => 'Közepes terem', 'date' => $dayAfter, 'time' => '21:00'],
            
            ['movie' => 'A vadon hangja', 'cinema' => 'Kisterem', 'date' => $dayAfter, 'time' => '15:00'],
            ['movie' => 'Wonka', 'cinema' => 'Kisterem', 'date' => $dayAfter, 'time' => '17:30'],
        ]);

        // Következő 4 nap
        for ($i = 3; $i <= 6; $i++) {
            $date = $today->copy()->addDays($i);
            
            $screeningsData = array_merge($screeningsData, [
                ['movie' => $movies->random()->title, 'cinema' => 'Nagy terem', 'date' => $date, 'time' => '14:00'],
                ['movie' => $movies->random()->title, 'cinema' => 'Nagy terem', 'date' => $date, 'time' => '17:30'],
                ['movie' => $movies->random()->title, 'cinema' => 'Nagy terem', 'date' => $date, 'time' => '20:30'],
                
                ['movie' => $movies->random()->title, 'cinema' => 'Közepes terem', 'date' => $date, 'time' => '15:00'],
                ['movie' => $movies->random()->title, 'cinema' => 'Közepes terem', 'date' => $date, 'time' => '18:00'],
                ['movie' => $movies->random()->title, 'cinema' => 'Közepes terem', 'date' => $date, 'time' => '21:00'],
                
                ['movie' => $movies->random()->title, 'cinema' => 'Kisterem', 'date' => $date, 'time' => '16:00'],
                ['movie' => $movies->random()->title, 'cinema' => 'Kisterem', 'date' => $date, 'time' => '18:30'],
            ]);
        }

        $created = 0;
        foreach ($screeningsData as $data) {
            $movie = $movies->firstWhere('title', $data['movie']);
            $cinema = $cinemas->firstWhere('name', $data['cinema']);

            if ($movie && $cinema) {
                $startTime = Carbon::parse($data['date']->format('Y-m-d') . ' ' . $data['time']);
                $endTime = $startTime->copy()->addMinutes($movie->duration + 15);

                Screening::firstOrCreate([
                    'movie_id' => $movie->id,
                    'cinema_id' => $cinema->id,
                    'start_time' => $startTime,
                ], [
                    'end_time' => $endTime,
                    'is_visible' => true,
                ]);

                $created++;
            }
        }

        $this->command->info('✅ ' . $created . ' vetítés létrehozva!');
    }
}