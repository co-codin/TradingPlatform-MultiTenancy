<?php

declare(strict_types=1);

namespace Modules\Transaction\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Transaction\Enums\TransactionMt5TypeName;
use Modules\Transaction\Models\TransactionsMt5Type;

final class TransactionMt5TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (TransactionMt5TypeName::getValues() as $value) {
            TransactionsMt5Type::query()->updateOrCreate(
                [
                    'name' => $value,
                    'title' => $value,
                    'mt5_id' => Str::uuid(), // TODO: integration with MT5
                ]
            );
        }
    }
}
