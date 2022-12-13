<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Services;

use LogicException;
use Modules\CommunicationProvider\Dto\CommunicationProviderDto;
use Modules\CommunicationProvider\Models\CommunicationProvider;

final class CommunicationProviderStorage
{
    /**
     * Store communication provider.
     *
     * @param  CommunicationProviderDto  $dto
     * @return CommunicationProvider
     */
    public function store(CommunicationProviderDto $dto): CommunicationProvider
    {
        $communicationProvider = CommunicationProvider::create($dto->toArray());

        if (! $communicationProvider) {
            throw new LogicException(__('Can not create communication provider'));
        }

        return $communicationProvider;
    }

    /**
     * Update communication provider.
     *
     * @param  CommunicationProvider  $communicationProvider
     * @param  CommunicationProviderDto  $dto
     * @return CommunicationProvider
     */
    public function update(CommunicationProvider $communicationProvider, CommunicationProviderDto $dto): CommunicationProvider
    {
        if (! $communicationProvider->update($dto->toArray())) {
            throw new LogicException(__('Can not update communication provider'));
        }

        return $communicationProvider;
    }

    /**
     * Delete communication provider.
     *
     * @param  CommunicationProvider  $communicationProvider
     * @return bool
     */
    public function delete(CommunicationProvider $communicationProvider): bool
    {
        if (! $communicationProvider->delete()) {
            throw new LogicException(__('Can not delete communication provider'));
        }

        return true;
    }
}
