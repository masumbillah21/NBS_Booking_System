<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
            RolesSeeder::class,
            UsersSeeder::class,
            ProviderSeeder::class,
            CategorySeeder::class,
            ServiceSeeder::class,
            ServiceCategorySeeder::class,
            AppointmentSeeder::class
        ]);
    }
}
