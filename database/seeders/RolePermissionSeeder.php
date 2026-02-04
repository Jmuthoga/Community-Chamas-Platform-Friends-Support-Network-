<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds_
     */
    public function run(): void
    {
        $roles = [
            'Admin',
            'production_manager',
            'sales_associate',
        ];
        for ($i = 0; $i < count($roles); $i++) {
            $result = Role::firstOrCreate(['name' => $roles[$i]]);
        }
        //creates permission
        $permissions = [
            //dashboard
            'dashboard_view',
            
            //role
            'role_create',
            'role_view',
            'role_update',
            'role_delete',
            'permission_view',
            //user
            'user_create',
            'user_view',
            'user_update',
            'user_delete',
            'user_suspend',

            //setting
            'website_settings',
            'contact_settings',
            'socials_settings',
            'style_settings',
            'custom_settings',
            'notification_settings',
            'website_status_settings',

        ];
        $admin = Role::where('name', 'Admin')->first();
        for ($i = 0; $i < count($permissions); $i++) {
            $permission = Permission::firstOrCreate(['name' => $permissions[$i]]);
            $admin->givePermissionTo($permission);
            $permission->assignRole($admin);
        }

        // Create users and assign roles
        $cashierUser = User::create([
            'name' => 'production Manager',
            'email' => 'manager@jminnovatechsolution.co.ke',
            'password' => bcrypt(12345678),
            'username' => uniqid(),
        ]);
        $salesUser = User::create([
            'name' => 'Mr Sales',
            'email' => 'sales@jminnovatechsolution.co.ke',
            'password' => bcrypt(12345678),
            'username' => uniqid(),
        ]);
        // Assign roles to users
        $production_managerRole = Role::where('name', 'production_manager')->first();
        $salesRole = Role::where('name', 'sales_associate')->first();

        $cashierUser->assignRole($production_managerRole);
        $salesUser->assignRole($salesRole);

        // Optionally, assign permissions to the cashier and sales_associate roles
        // You can customize these permissions as needed
        $production_managerPermissions = [

        ];

        $salesPermissions = [

        ];

        foreach ($production_managerPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $production_managerRole->givePermissionTo($permission);
        }

        foreach ($salesPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $salesRole->givePermissionTo($permission);
        }
    }
}
