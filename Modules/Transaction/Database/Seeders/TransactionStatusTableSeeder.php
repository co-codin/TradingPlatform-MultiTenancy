<?php

declare(strict_types=1);

namespace Modules\Transaction\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Transaction\Enums\TransactionStatusName;
use Modules\Transaction\Models\TransactionStatus;

final class TransactionStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (TransactionStatusName::getValues() as $value) {
            TransactionStatus::query()->updateOrCreate(
                [
                    'name' => $value,
                    'title' => $value,
                    'is_active' => true,
                    'is_valid' => true,
                ]
            );
        }
    }
}
