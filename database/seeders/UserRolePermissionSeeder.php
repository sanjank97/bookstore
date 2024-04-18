<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            'create-role',
            'edit-role',
            'delete-role',
            'create-user',
            'edit-user',
            'delete-user',
            'create-product',
            'edit-product',
            'delete-product'
        ];
 
        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Admin']);
        $productManagerRole = Role::create(['name' => 'Product Manager']);

        // Sync all permissions to super admin role
        $permissions = Permission::pluck('id')->all();
        $superAdminRole->syncPermissions($permissions);

        // Assign specific permissions to admin role
        $adminRole->givePermissionTo([
            'create-user',
            'edit-user',
            'delete-user',
            'create-product',
            'edit-product',
            'delete-product'
        ]);
  
        // Assign specific permissions to product manager role
        $productManagerRole->givePermissionTo([
            'create-product',
            'edit-product',
            'delete-product'
        ]);

        // Creating Super Admin User
        $superAdminUser = User::create([
            'name' => 'Sanjan', 
            'mobile' =>'8340106146',
            'email' => 'sanjank.mvteams@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $superAdminUser->assignRole($superAdminRole);

        // Creating Admin User
        $adminUser = User::create([
            'name' => 'krishna', 
            'mobile' =>'8284910963',
            'email' => 'krishna@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $adminUser->assignRole($adminRole);

        // Creating Product Manager User
        $productManagerUser = User::create([
            'name' => 'neha', 
            'mobile' =>'1234567890',
            'email' => 'neha@gmail.com',
            'password' => Hash::make('12345678')
        ]);
        $productManagerUser->assignRole($productManagerRole);
    }
}
