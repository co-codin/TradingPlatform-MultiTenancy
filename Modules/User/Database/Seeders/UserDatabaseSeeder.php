<?php
declare(strict_types=1);

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

class UserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $user = User::factory()->create([
            'email' => 'admin@stoxtech.com'
        ]);

        $user->assignRole(Role::find(1));
    }
}
