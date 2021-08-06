<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();
        $admin = Role::whereName('Admin')->first();

        foreach ($permissions as $permission) {
            DB::table('role_permissions')->insert([
                'role_id' => $admin->id,
                'permission_id' => $permission->id,
            ]);
        }

        $manager = Role::whereName('Manager')->first();
        foreach ($permissions as $permission) {
            if(!in_array($permission->name, ['roles edit'])){
                DB::table('role_permissions')->insert([
                    'role_id' => $manager->id,
                    'permission_id' => $permission->id,
                ]);
            }
        }


        $viewer = Role::whereName('Viewer')->first();
        foreach ($permissions as $permission) {
            $viewer_roles = ['roles views', 'products views', 'users views'];
            if(in_array($permission->name, $viewer_roles)){
                DB::table('role_permissions')->insert([
                    'role_id' => $viewer->id,
                    'permission_id' => $permission->id,
                ]);
            }
        }
    }
}
