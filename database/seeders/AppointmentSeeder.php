<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            DB::table('appointments')->insert([
                'client_id' => User::inRandomOrder()->first()->id,
                'service_id' => Service::inRandomOrder()->first()->id,
                'staff_id' => User::inRandomOrder()->first()->id,
                'appointment_date' => $faker->date(),
                'appointment_time' => $faker->time(),
                'status' => $faker->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
