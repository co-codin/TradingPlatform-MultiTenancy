<?php

namespace Modules\Transaction\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Transaction\Enums\TransactionMethodEnum;
use Modules\Transaction\Models\TransactionsMethod;

class TransactionMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (TransactionMethodEnum::getValues() as $value) {
            TransactionsMethod::query()->updateOrCreate(
                [
                    'name' => $value,
                    'title' => $value,
                    'is_active' => true,
                ]
            );
        }
    }
}
