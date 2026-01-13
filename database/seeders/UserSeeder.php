<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'super-admin@mail.com',
            'password' => bcrypt('password'),
            'role' => RoleEnum::SUPER_ADMIN->value,
            'email_verified_at' => now(),
        ]);

        // User
        User::create([
            'name' => 'User',
            'email' => 'user@mail.com',
            'password' => bcrypt('password'),
            'role' => RoleEnum::USER->value,
            'email_verified_at' => now(),
        ]);
    }
}
