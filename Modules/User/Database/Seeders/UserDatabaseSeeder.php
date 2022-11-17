<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

class UserDatabaseSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@stoxtech.com',
            'password' => Hash::make('password'),
        ]);

//        $role = Role::factory()->create([
//            'name' => DefaultRole::ADMIN,
//            'key' => DefaultRole::ADMIN
//        ]);
//
//        $user->assignRole($role);
    }
}
