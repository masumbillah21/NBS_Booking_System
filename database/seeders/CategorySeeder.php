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

        $categories = [
            [
                'category_name' => 'Saloon',
                'subcategories' => [
                    ['category_name' => 'Hairdresser', 'description' => 'Professional hair styling and cutting services.'],
                    ['category_name' => 'Manicure/Pedicure', 'description' => 'Nail care services including manicures and pedicures.'],
                    ['category_name' => 'Facial Services', 'description' => 'Facial treatments and skincare services.'],
                    ['category_name' => 'Spa Treatments', 'description' => 'Relaxation and rejuvenation spa services.'],
                ],
            ],
            [
                'category_name' => 'Fitness',
                'subcategories' => [
                    ['category_name' => 'Personal Training', 'description' => 'Customized fitness training sessions.'],
                    ['category_name' => 'Yoga/Pilates', 'description' => 'Yoga and Pilates classes for strength and flexibility.'],
                    ['category_name' => 'Group Classes', 'description' => 'Group fitness classes for various disciplines.'],
                    ['category_name' => 'Nutrition Counseling', 'description' => 'Dietary guidance and nutrition planning.'],
                ],
            ],
            [
                'category_name' => 'Healthcare',
                'subcategories' => [
                    ['category_name' => 'General Physician', 'description' => 'Primary healthcare services and medical consultations.'],
                    ['category_name' => 'Dermatologist', 'description' => 'Skin care and dermatology treatments.'],
                    ['category_name' => 'Dentist', 'description' => 'Dental care services including check-ups and treatments.'],
                    ['category_name' => 'Eye Specialist', 'description' => 'Eye care services and vision correction treatments.'],
                ],
            ],
            [
                'category_name' => 'Home Services',
                'subcategories' => [
                    ['category_name' => 'Plumbing Services', 'description' => 'Residential plumbing repairs and installations.'],
                    ['category_name' => 'Electrician', 'description' => 'Electrical services for home installations and repairs.'],
                    ['category_name' => 'House Cleaning', 'description' => 'Professional house cleaning services.'],
                    ['category_name' => 'Appliance Repair', 'description' => 'Repair services for household appliances.'],
                ],
            ],
            [
                'category_name' => 'Events & Entertainment',
                'subcategories' => [
                    ['category_name' => 'Event Planner', 'description' => 'Event planning and coordination services.'],
                    ['category_name' => 'DJ Services', 'description' => 'Professional DJ services for events and parties.'],
                    ['category_name' => 'Photography', 'description' => 'Photography services for events and portraits.'],
                    ['category_name' => 'Catering Services', 'description' => 'Food catering services for events and gatherings.'],
                ],
            ],
            // Add more categories and subcategories as needed
        ];


        foreach ($categories as $category) {
            $categoryId = DB::table('categories')->insertGetId([
                'category_name' => $category['category_name'],
                'description' => isset($category['description']) ? $category['description'] : '',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($category['subcategories'] as $subcategory) {
                DB::table('categories')->insert([
                    'category_name' => $subcategory['category_name'],
                    'parent_id' => $categoryId,
                    'description' => isset($subcategory['description']) ? $subcategory['description'] : '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
