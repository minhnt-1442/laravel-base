<?php

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        // Reset cached roles and permissions.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Create roles.
        $roles = [
            'admin',
            'member'
        ];
        
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
        
        // Create permissions.
        $permissions = [
            'get_list_user',
            'create_user',
            'update_user',
            'delete_user',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        };
        
        //Assign permissions for admin.
        Role::findByName('admin')->givePermissionTo($permissions);
        
        //Assign permissions for member.
        $permissionMember = Permission::whereIn('name', [
            'get_list_user',
            'update_user'
        ])->get();
        Role::findByName('member')->givePermissionTo($permissionMember);
    }
}
