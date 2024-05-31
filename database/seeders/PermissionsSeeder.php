<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
  {
    $menus = [
      [
        "name" => "Dashbaord View",
        "permission" => "dashboard.view"
      ],
      [
        "name" => "Role View",
        "permission" => "role.view"
      ],
      [
        "name" => "Role Create",
        "permission" => "role.create"
      ],
      [
        "name" => "Update Role",
        "permission" => "role.update"
      ],
      [
        "name" => "Delete Role",
        "permission" => "role.delete"
      ],
      [
        "name" => "Permission View",
        "permission" => "permission.view"
      ],
      [
        "name" => "Permission Create",
        "permission" => "permission.create"
      ],
      [
        "name" => "Permission Update",
        "permission" => "permission.update"
      ],
      [
        "name" => "Permission Delete",
        "permission" => "permission.delete"
      ],
      [
        "name" => "User View",
        "permission" => "user.view"
      ],
      [
        "name" => "User Create",
        "permission" => "user.create"
      ],
      [
        "name" => "User Update",
        "permission" => "user.update"
      ],
      [
        "name" => "User Delete",
        "permission" => "user.delete"
      ],
      [
        "name" => "Setting View",
        "permission" => "setting.view"
      ],
      [
        "name" => "Setting Create",
        "permission" => "setting.create"
      ],
      [
        "name" => "Setting Update",
        "permission" => "setting.update"
      ],
      [
        "name" => "Setting Delete",
        "permission" => "setting.delete"
      ],
    ];  

    $this->createMenu($menus);
  }

  private function createMenu($datas)
  {
    foreach ($datas as $menu) {
      $permission = new Permission([
        'name' => $menu['name'],
        'permission' => $menu['permission'],
      ]);
      $permission->save();
    }
  }
}
