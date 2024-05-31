<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        
        $users = [
            [
                'name' => 'Masum',
                'email' => 'mbillah21@gmail.com',
                'password' => '12345678',
                'designation' => 'Software Engineer',
                'email_verified_at' => now(),
                'status' => 1,
            ],
            [
                'name' => 'MD. Farok Ahmed',
                'email' => 'mdfarokahmed280@gmail.com',
                'password' => '12345678',
                'designation' => 'Software Engineer',
                'email_verified_at' => now(),
                'status' => 1,
            ],
            [
                'name' => 'Rakibul Islam',
                'email' => 'devrakib.io@gmail.com',
                'password' => '12345678',
                'designation' => 'Software Engineer',
                'email_verified_at' => now(),
                'status' => 1,
            ],
            [
                'name' => 'Tarek H.',
                'email' => 'tarekhn175@gmail.com',
                'password' => '12345678',
                'designation' => 'Software Engineer',
                'email_verified_at' => now(),
                'status' => 1,
            ],
            [
                'name' => 'Emon Islam',
                'email' => 'smemonislam6@gmail.com',
                'password' => '12345678',
                'designation' => 'Software Engineer',
                'email_verified_at' => now(),
                'status' => 1,
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'designation' => $userData['designation'],
                'email_verified_at' => $userData['email_verified_at'],
            ]);
            $user->roles()->attach($adminRole);
        }
    }
}
