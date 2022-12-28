<?php

namespace Modules\Transaction\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Transaction\Enums\TransactionMethodName;
use Modules\Transaction\Enums\TransactionStatusName;
use Modules\Transaction\Models\TransactionsMethod;
use Modules\Transaction\Models\TransactionStatus;

class TransactionMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (TransactionMethodName::getValues() as $value) {
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
