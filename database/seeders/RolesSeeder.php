<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create([
            'name' => 'Admin',
        ]);
        $admin->permissions()->attach(Permission::all());
        
        Role::create([
            'name' => 'Service Provider',
        ]);
        Role::create([
            'name' => 'Staff',
        ]);
        Role::create([
            'name' => 'Client',
        ]);
        
    }
}
