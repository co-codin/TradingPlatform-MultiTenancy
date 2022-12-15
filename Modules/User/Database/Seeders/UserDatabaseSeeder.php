<?php

declare(strict_types=1);

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Role\Models\Role;

final class UserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        dd(
            Role::where(function ($query) {
                $query->where('brand_id', 1)
                    ->orWhereNull('brand_id');
            })->get());
        //
    }
}
