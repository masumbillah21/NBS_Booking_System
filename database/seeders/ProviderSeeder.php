<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 10) as $index) {
            DB::table('providers')->insert([
                'user_id' => 2,
                'company_name' => $faker->company,
                'description' => $faker->paragraph,
                'logo' => $faker->imageUrl(400, 300, 'business', true),
                'address' => $faker->address,
                'phone_number' => $faker->phoneNumber,
                'email' => $faker->unique()->companyEmail,
                'status' => $faker->numberBetween(0, 1),
                'slug' => Str::slug($faker->company . '-' . $index)
            ]);
        }
    }
}
