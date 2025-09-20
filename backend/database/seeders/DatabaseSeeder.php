<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Super Admin User directly without factory
        User::create([
            'name' => 'Northline Developer',
            'email' => 'developer@northline-dev.com',
            'password' => Hash::make('12345678'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);
    }
}