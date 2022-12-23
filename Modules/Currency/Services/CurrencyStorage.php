<?php

declare(strict_types=1);

namespace Modules\Currency\Services;

use LogicException;
use Modules\Currency\Dto\CurrencyDto;
use Modules\Currency\Models\Currency;

final class CurrencyStorage
{
    /**
     * Store.
     *
     * @param CurrencyDto $dto
     * @return Currency
     */
    public function store(CurrencyDto $dto): Currency
    {
        if (! $currency = Currency::query()->create($dto->toArray())) {
            throw new LogicException(__('Can not create currency'));
        }

        return $currency;
    }

    /**
     * Update.
     *
     * @param Currency $currency
     * @param CurrencyDto $dto
     * @return Currency
     * @throws LogicException
     */
    public function update(Currency $currency, CurrencyDto $dto): Currency
    {
        if (! $currency->update($dto->toArray())) {
            throw new LogicException(__('Can not update currency'));
        }

        return $currency;
    }

    /**
     * Delete.
     *
     * @param Currency $currency
     * @return bool
     */
    public function destroy(Currency $currency): bool
    {
        if (! $currency->delete()) {
            throw new LogicException(__('Can not destroy currency'));
        }

        return true;
    }
}
