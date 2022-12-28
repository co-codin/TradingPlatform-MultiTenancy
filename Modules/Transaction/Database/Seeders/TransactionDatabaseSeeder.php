<?php

namespace Modules\Transaction\Database\Seeders;

use Illuminate\Database\Seeder;

class TransactionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            TransactionMt5TypeTableSeeder::class,
            TransactionStatusTableSeeder::class,
            TransactionMethodTableSeeder::class,
        ]);
    }
}
