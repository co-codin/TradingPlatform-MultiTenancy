<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

class UserDatabaseSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'email' => 'admin@admin.ru'
        ]);

        $user->assignRole(Role::find(1));
    }
}
