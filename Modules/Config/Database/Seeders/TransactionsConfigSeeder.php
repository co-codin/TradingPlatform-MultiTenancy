<?php

declare(strict_types=1);

namespace Modules\Config\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Config\Enums\ConfigDataTypeEnum;
use Modules\Config\Enums\ConfigEnum;
use Modules\Config\Enums\ConfigTypeEnum;
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
                'data_type' => ConfigDataTypeEnum::JSON,
                'name' => ConfigEnum::CUSTOMER_RESTRICTIONS,
            ],
            [
                'value' => [
                    'USD' => [
                        'min_deposit' => 1,
                        'min_withdraw' => 1,
                    ],
                    'EUR' => [
                        'min_deposit' => 1,
                        'min_withdraw' => 1,
                    ],
                ],
            ]
        );

        $configType->configs()->updateOrInsert(
            ['data_type' => ConfigDataTypeEnum::INTEGER, 'name' => ConfigEnum::CHANGE_DEPARTMENT_DELAY],
            ['value' => 5]
        );
    }
}
