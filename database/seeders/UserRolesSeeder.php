<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\Models\User::all() as $user) {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $user->role_id,
            ]);
        }
    }
}
