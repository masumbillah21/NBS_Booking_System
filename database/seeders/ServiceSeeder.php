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
                'service_name' => $faker->words(2, true),
                'description' => $faker->optional()->paragraph,
                'duration' => $faker->numberBetween(30, 240),
                'price' => $faker->numberBetween(100, 1000),
                'provider_id' => $faker->numberBetween(1, 10),
                'category_id' => $faker->numberBetween(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
