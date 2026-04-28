<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@app.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('password')]
        );
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            $superAdmin->assignRole($superAdminRole);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@app.com'],
            ['name' => 'Admin', 'password' => bcrypt('password')]
        );
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $admin->assignRole($adminRole);
        }
    }
}
