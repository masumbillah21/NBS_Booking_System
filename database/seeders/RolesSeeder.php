<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

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
        
        $provider = Role::create([
            'name' => 'Provider',
        ]);

        $providerPermission = Permission::whereIn(
            'permission',
            [
                "dashboard.view", 
                'service.view',
                'service.create',
                'service.update',
                'service.delete',
                'appointment.view',
                'appointment.create',
                'appointment.update',
                'appointment.delete',
                'appointment.report.view'
            ]
        )->get();
        if ($provider && $providerPermission) {
            $provider->permissions()->attach($providerPermission);
        }

        $staff = Role::create([
            'name' => 'Staff',
        ]);
        $staffPermissions = Permission::whereIn(
            'permission',
            [
                "dashboard.view", 
                'appointment.view',
                'appointment.create',
                'appointment.update',
                'appointment.delete',
            ]
        )->get();
        if ($staff && $staffPermissions) {
            $staff->permissions()->attach($staffPermissions);
        }

        $client = Role::create([
            'name' => 'Client',
        ]);

        $clientPermission = Permission::whereIn(
            'permission',
            [
                "dashboard.view", 
                'customer.view',
                'customer.edit',
                'customer.feedback',
                'customer.reappointment',
                'customer.cancel'
            ]
        )->get();
        if ($client && $clientPermission) {
            $client->permissions()->attach($clientPermission);
        }
        
    }
}
