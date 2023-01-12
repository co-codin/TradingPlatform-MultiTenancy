<?php

declare(strict_types=1);

namespace Modules\Config\Database\Seeders;

use Illuminate\Database\Seeder;

final class ConfigDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            TransactionsConfigSeeder::class,
        ]);
    }
}
