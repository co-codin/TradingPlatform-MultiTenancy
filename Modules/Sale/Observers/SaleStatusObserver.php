<?php

declare(strict_types=1);

namespace Modules\Sale\Observers;

use Exception;
use Modules\Sale\Enums\SaleStatusNameEnum;
use Modules\Sale\Models\SaleStatus;

final class SaleStatusObserver
{
    /**
     * Handle the SaleStatus "saving" event.
     *
     * @param SaleStatus $saleStatus
     * @return void
     * @throws Exception
     */
    public function saving(SaleStatus $saleStatus): void
    {
        if (
            $saleStatus->department->isConversion()
            && ! in_array($saleStatus->name, SaleStatusNameEnum::conversionSaleStatusList())
        ) {
            throw new Exception('For conversion departments, only conversion statuses can be linked.');
        }

        if (
            $saleStatus->department->isRetention()
            && ! in_array($saleStatus->name, SaleStatusNameEnum::retentionSaleStatusList())
        ) {
            throw new Exception('For retention departments, only retention statuses can be linked.');
        }
    }
}
