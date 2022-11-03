<?php
declare(strict_types=1);

namespace Modules\Worker\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Role\Models\Role;
use Modules\Worker\Models\User;

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
