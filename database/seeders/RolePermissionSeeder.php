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
            'Accountant',
            'Chairperson',
            'Member',
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
        $AdminUser = User::create([
            'name' => 'Admin',
            'email' => 'johnmuthogakanyingi@gmail.com',
            'phone' => '254700000001',
            'password' => bcrypt('12345678'),
            'username' => uniqid(), 
        ]);

        $AccountantUser = User::create([
            'name' => 'Accountant',
            'email' => 'accountant@jminnovatechsolution.co.ke',
            'phone' => '254700000002',
            'password' => bcrypt('12345678'),
            'username' => uniqid(),
        ]);

        $ChairpersonUser = User::create([
            'name' => 'Chairperson',
            'email' => 'chairperson@jminnovatechsolution.co.ke',
            'phone' => '254700000003',
            'password' => bcrypt('12345678'),
            'username' => uniqid(),
        ]);

        $MemberUser = User::create([
            'name' => 'Member',
            'email' => 'member@example.com',
            'phone' => '254700000004',
            'password' => bcrypt('12345678'),
            'username' => uniqid(),
        ]);


        // Assign roles to users
        $AdminRole = Role::where('name', 'Admin')->first();
        $AccountantRole = Role::where('name', 'Accountant')->first();
        $ChairpersonRole = Role::where('name', 'Chairperson')->first();
        $MemberRole = Role::where('name', 'Member')->first();

        $AdminUser->assignRole($AdminRole);
        $AccountantUser->assignRole($AccountantRole);
        $ChairpersonUser->assignRole($ChairpersonRole);
        $MemberUser->assignRole($MemberRole);

        // Optionally, assign permissions to the Accountant and Chairperson roles
        // You can customize these permissions as needed
        $AccountantPermissions = [

        ];

        $ChairpersonPermissions = [

        ];

        $MemberPermissions = [
            
        ];

        foreach ($MemberPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $MemberRole->givePermissionTo($permission);
        }

        foreach ($AccountantPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $AccountantRole->givePermissionTo($permission);
        }

        foreach ($ChairpersonPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $ChairpersonRole->givePermissionTo($permission);
        }
    }
}
