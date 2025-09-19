<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\EventZone;
use App\Models\Ticket;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Attendee;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create Super Admin User
        User::factory()->create([
            'name' => 'Northline Developer',
            'email' => 'developer@northline-dev.com',
            'password' => bcrypt('12345678'),
            'role' => 'super_admin',
        ]);

       
    }
 }