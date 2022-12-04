<?php

declare(strict_types=1);

namespace Modules\Sale\Services;

use LogicException;
use Modules\Sale\Dto\SaleStatusDto;
use Modules\Sale\Models\SaleStatus;

final class SaleStatusStorage
{
    /**
     * Store salestatus.
     *
     * @param SaleStatusDto $dto
     * @return SaleStatus
     */
    public function store(SaleStatusDto $dto): SaleStatus
    {
        $saleStatus = SaleStatus::create($dto->toArray());

        if (!$saleStatus) {
            throw new LogicException(__('Can not create salestatus'));
        }

        return $saleStatus;
    }

    /**
     * Update salestatus.
     *
     * @param SaleStatus $saleStatus
     * @param SaleStatusDto $dto
     * @return SaleStatus
     * @throws LogicException
     */
    public function update(SaleStatus $saleStatus, SaleStatusDto $dto): SaleStatus
    {
        if (!$saleStatus->update($dto->toArray())) {
            throw new LogicException(__('Can not update salestatus'));
        }

        return $saleStatus;
    }

    /**
     * Delete salestatus.
     *
     * @param SaleStatus $saleStatus
     * @return bool
     */
    public function delete(SaleStatus $saleStatus): bool
    {
        if (!$saleStatus->delete()) {
            throw new LogicException(__('Can not delete salestatus'));
        }

        return true;
    }
}
