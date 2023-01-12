<?php

declare(strict_types=1);

namespace Modules\Config\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Config\Enums\ConfigName;
use Modules\Config\Enums\ConfigType as ConfigTypeEnum;
use Modules\Config\Enums\DataType;
use Modules\Config\Models\ConfigType;

final class TransactionsConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        /** @var ConfigType $configType */
        $configType = ConfigType::query()->updateOrCreate([
            'name' => ConfigTypeEnum::TRANSACTION,
        ]);

        $configType->configs()->updateOrCreate(
            [
                'data_type' => DataType::JSON,
                'name' => ConfigName::CUSTOMER_RESTRICTIONS,
            ],
            [
                'value' => json_encode([
                    'USD' => [
                        'min_deposit' => 123,
                        'min_withdraw' => 123,
                    ],
                    'EUR' => [
                        'min_deposit' => 123,
                        'min_withdraw' => 123,
                    ],
                ]),
            ]
        );
    }
}
