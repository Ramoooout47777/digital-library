<?php
// database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $admin = Role::create(['name' => 'admin']);
        $superAdmin = Role::create(['name' => 'super-admin']);
        $customer = Role::create(['name' => 'customer']);

        // Assign permissions to admin
        $admin->givePermissionTo([
            'view dashboard',
            'manage banners',
            'manage books',
            'manage users',
            'manage orders',
        ]);

        // Super admin gets all permissions
        $superAdmin->givePermissionTo(Permission::all());
    }
}