<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Services;

use LogicException;
use Modules\TelephonyProvider\Dto\TelephonyProviderDto;
use Modules\TelephonyProvider\Models\TelephonyProvider;

final class TelephonyProviderStorage
{
    /**
     * Store telephony provider.
     *
     * @param  TelephonyProviderDto  $dto
     * @return TelephonyProvider
     */
    public function store(TelephonyProviderDto $dto): TelephonyProvider
    {
        $telephonyProvider = TelephonyProvider::create($dto->toArray());

        if (! $telephonyProvider) {
            throw new LogicException(__('Can not create telephony provider'));
        }

        return $telephonyProvider;
    }

    /**
     * Update telephony provider.
     *
     * @param  TelephonyProvider  $telephonyProvider
     * @param  TelephonyProviderDto  $dto
     * @return TelephonyProvider
     */
    public function update(TelephonyProvider $telephonyProvider, TelephonyProviderDto $dto): TelephonyProvider
    {
        if (! $telephonyProvider->update($dto->toArray())) {
            throw new LogicException(__('Can not update telephony provider'));
        }

        return $telephonyProvider;
    }

    /**
     * Delete telephony provider.
     *
     * @param  TelephonyProvider  $telephonyProvider
     * @return bool
     */
    public function delete(TelephonyProvider $telephonyProvider): bool
    {
        if (! $telephonyProvider->delete()) {
            throw new LogicException(__('Can not delete telephony provider'));
        }

        return true;
    }
}
