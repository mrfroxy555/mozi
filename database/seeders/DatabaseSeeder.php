<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Szerepkörök
        $this->call(RoleSeeder::class);
        
        // Admin felhasználó létrehozása
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@cinema.hu',
            'password' => bcrypt('password'),
            'role_id' => 3, // Owner
        ]);

        // Mozitermek, árkategóriák, székek
        $this->call([
            CinemaSeeder::class,
            SeatCategorySeeder::class,
            SeatSeeder::class,
        ]);

        // Filmek és vetítések
        $this->call([
            MovieSeeder::class,
            ScreeningSeeder::class,
        ]);

        // Teszt felhasználók és foglalások (opcionális)
        $this->call([
            TestUserSeeder::class,
            TestBookingSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ Adatbázis teljesen feltöltve!');
        $this->command->info('');
        $this->command->info('👥 BEJELENTKEZÉSI ADATOK:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('🔑 Tulajdonos/Admin:');
        $this->command->info('   Email: admin@cinema.hu');
        $this->command->info('   Jelszó: password');
        $this->command->info('');
        $this->command->info('👤 Teszt felhasználó:');
        $this->command->info('   Email: user@test.hu');
        $this->command->info('   Jelszó: password');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}