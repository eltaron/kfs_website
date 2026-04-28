<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::updateOrCreate(['name' => 'Super Admin']);
        Role::updateOrCreate(['name' => 'Admin']);
        Role::updateOrCreate(['name' => 'Editor']);
        Role::updateOrCreate(['name' => 'Citizen']);
    }
}
