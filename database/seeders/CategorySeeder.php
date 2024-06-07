<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create some parent categories first
        foreach (range(1, 5) as $index) {
            DB::table('categories')->insert([
                'category_name' => $faker->word,
                'parent_id' => null,
                'description' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Get the parent categories' IDs
        $parentIds = DB::table('categories')->pluck('id')->toArray();

        // Create some child categories
        foreach (range(1, 20) as $index) {
            DB::table('categories')->insert([
                'category_name' => $faker->word,
                'parent_id' => $faker->randomElement($parentIds),
                'description' => $faker->sentence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
