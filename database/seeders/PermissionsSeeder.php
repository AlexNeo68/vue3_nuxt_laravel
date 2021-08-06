<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            ['name'=>'roles views'],
            ['name'=>'roles edit'],
            ['name'=>'permissions views'],
            ['name'=>'permissions edit'],
            ['name'=>'users views'],
            ['name'=>'users edit'],
            ['name'=>'products views'],
            ['name'=>'products edit'],
        ]);
    }
}
