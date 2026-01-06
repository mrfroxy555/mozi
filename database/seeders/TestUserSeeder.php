<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Teszt Felhasználó',
                'email' => 'user@test.hu',
                'password' => Hash::make('password'),
                'role_id' => Role::USER,
            ],
            [
                'name' => 'Teszt Admin',
                'email' => 'admin2@test.hu',
                'password' => Hash::make('password'),
                'role_id' => Role::ADMIN,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('✅ ' . count($users) . ' teszt felhasználó létrehozva!');
    }
}