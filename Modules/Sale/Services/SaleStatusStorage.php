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
        $salestatus = SaleStatus::create($dto->toArray());

        if (!$salestatus) {
            throw new LogicException(__('Can not create salestatus'));
        }

        return $salestatus;
    }

    /**
     * Update salestatus.
     *
     * @param SaleStatus $salestatus
     * @param SaleStatusDto $dto
     * @return SaleStatus
     * @throws LogicException
     */
    public function update(SaleStatus $salestatus, SaleStatusDto $dto): SaleStatus
    {
        if (!$salestatus->update($dto->toArray())) {
            throw new LogicException(__('Can not update salestatus'));
        }

        return $salestatus;
    }

    /**
     * Delete salestatus.
     *
     * @param SaleStatus $salestatus
     * @return bool
     */
    public function delete(SaleStatus $salestatus): bool
    {
        if (!$salestatus->delete()) {
            throw new LogicException(__('Can not delete salestatus'));
        }

        return true;
    }
}
