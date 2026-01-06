<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Screening;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestBookingSeeder extends Seeder
{
    public function run(): void
    {
        $screenings = Screening::with('cinema')->limit(5)->get();
        $user = User::where('role_id', 1)->first();

        if ($screenings->isEmpty()) {
            $this->command->error('Nincsenek vetítések az adatbázisban!');
            return;
        }

        $bookingsCreated = 0;

        foreach ($screenings as $screening) {
            // Véletlenszerű számú jegy (2-5 db)
            $ticketCount = rand(2, 5);
            
            // Véletlenszerű székek kiválasztása
            $availableSeats = Seat::where('cinema_id', $screening->cinema_id)
                ->whereNotIn('id', function($query) use ($screening) {
                    $query->select('seat_id')
                          ->from('tickets')
                          ->where('screening_id', $screening->id);
                })
                ->inRandomOrder()
                ->limit($ticketCount)
                ->get();

            if ($availableSeats->count() < $ticketCount) {
                continue;
            }

            // Foglalás létrehozása
            $totalPrice = $availableSeats->sum(fn($seat) => $seat->seatCategory->price);

            $booking = Booking::create([
                'user_id' => $user ? $user->id : null,
                'screening_id' => $screening->id,
                'guest_name' => $user ? null : 'Vendég Teszt',
                'guest_email' => $user ? null : 'vendeg@test.hu',
                'total_price' => $totalPrice,
                'status' => 'confirmed',
            ]);

            // Jegyek létrehozása
            foreach ($availableSeats as $seat) {
                Ticket::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seat->id,
                    'screening_id' => $screening->id,
                    'price' => $seat->seatCategory->price,
                ]);
            }

            $bookingsCreated++;
        }

        $this->command->info('✅ ' . $bookingsCreated . ' teszt foglalás létrehozva!');
    }
}