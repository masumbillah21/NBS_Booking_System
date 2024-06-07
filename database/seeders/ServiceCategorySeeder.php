<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Get all category and service IDs
        $categoryIds = DB::table('categories')->pluck('id')->toArray();
        $serviceIds = DB::table('services')->pluck('id')->toArray();

        // Track existing category-service pairs
        $existingPairs = [];

        // Create service-category relationships
        foreach ($serviceIds as $serviceId) {
            // Assign each service to 1 to 3 categories
            foreach (range(1, $faker->numberBetween(1, 3)) as $index) {
                $categoryId = $faker->randomElement($categoryIds);

                // Check if the pair already exists
                $pair = $categoryId . '-' . $serviceId;
                if (!in_array($pair, $existingPairs)) {
                    // Insert the new pair
                    DB::table('service_category')->insert([
                        'category_id' => $categoryId,
                        'service_id' => $serviceId,
                    ]);

                    // Add the pair to the set of existing pairs
                    $existingPairs[] = $pair;
                }
            }
        }
    }
}
