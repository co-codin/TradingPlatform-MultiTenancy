<?php

namespace Modules\Worker\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Worker\Models\Worker;

class WorkersTableSeeder extends Seeder
{
    public function run()
    {
        Worker::factory()->create([
            'username' => 'admin',
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@stoxtech.com',
            'password' => Hash::make('password'),
        ]);
    }
}
