<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Superadmin User (Role: admin)
        User::firstOrCreate(
            ['email' => 'superadmin@tensitrack.my.id'],
            [
                'name' => 'Super Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Admin User (Role: admin)
        User::firstOrCreate(
            ['email' => 'admin@tensitrack.my.id'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Default Admin User (Existing)
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // Client User
        User::firstOrCreate(
            ['email' => 'client@example.com'],
            [
                'name' => 'User Client',
                'role' => 'client',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
    }
}