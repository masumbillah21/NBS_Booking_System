<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            DB::table('services')->insert([
                'service_name' => $faker->name(),
                'description' => $faker->optional()->paragraph,
                'duration' => $faker->numberBetween(30, 240), // Duration in minutes
                'price' => $faker->numberBetween(100, 1000), // Price in arbitrary currency units
                'provider_id' => $faker->numberBetween(1, 10), // Assuming you have providers with IDs from 1 to 10
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
