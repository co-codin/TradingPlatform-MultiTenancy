<?php

declare(strict_types=1);

namespace Modules\Sale\Services;

use LogicException;
use Modules\Sale\Dto\SaleDto;
use Modules\Sale\Models\SaleStatus;

final class SaleStorage
{
    /**
     * Store sale.
     *
     * @param SaleDto $dto
     * @return SaleStatus
     */
    public function store(SaleDto $dto): SaleStatus
    {
        $sale = SaleStatus::create($dto->toArray());

        if (!$sale) {
            throw new LogicException(__('Can not create sale'));
        }

        return $sale;
    }

    /**
     * Update sale.
     *
     * @param SaleStatus $sale
     * @param SaleDto $dto
     * @return SaleStatus
     * @throws LogicException
     */
    public function update(SaleStatus $sale, SaleDto $dto): SaleStatus
    {
        if (!$sale->update($dto->toArray())) {
            throw new LogicException(__('Can not update sale'));
        }

        return $sale;
    }

    /**
     * Delete sale.
     *
     * @param SaleStatus $sale
     * @return bool
     */
    public function delete(SaleStatus $sale): bool
    {
        if (!$sale->delete()) {
            throw new LogicException(__('Can not delete sale'));
        }

        return true;
    }
}
