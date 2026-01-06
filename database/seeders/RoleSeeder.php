<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'user', 'display_name' => 'Felhasználó'],
            ['name' => 'admin', 'display_name' => 'Adminisztrátor'],
            ['name' => 'owner', 'display_name' => 'Tulajdonos'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}