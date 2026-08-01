<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'manage_users',
            'manage_books',
            'manage_orders',
            'manage_categories',
            'manage_authors',
            'manage_publishers',
            'manage_coupons',
            'manage_banners',
            'manage_settings',
            'view_reports'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'manage_books',
            'manage_orders',
            'manage_categories',
            'manage_authors',
            'manage_publishers',
            'manage_banners',
            'view_reports'
        ]);

        $editor = Role::create(['name' => 'editor']);
        $editor->givePermissionTo([
            'manage_books',
            'manage_categories',
            'manage_authors',
            'manage_publishers'
        ]);

        $customer = Role::create(['name' => 'customer']);

        // Create admin user
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@bookstore.com',
            'password' => Hash::make('password123'),
            'phone' => '012345678',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $user->assignRole('super-admin');

        // Create test customer
        $customerUser = User::create([
            'name' => 'Test Customer',
            'email' => 'customer@bookstore.com',
            'password' => Hash::make('password123'),
            'phone' => '098765432',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $customerUser->assignRole('customer');

        echo "Roles and permissions created successfully!\n";
        echo "Admin: admin@bookstore.com / password123\n";
        echo "Customer: customer@bookstore.com / password123\n";
    }
}