<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Services;

use LogicException;
use Modules\CommunicationProvider\Dto\CommunicationExtensionDto;
use Modules\CommunicationProvider\Models\CommunicationProvider;

final class CommunicationExtensionStorage
{
    /**
     * Store communication provider.
     *
     * @param  CommunicationExtensionDto  $dto
     * @return CommunicationProvider
     */
    public function store(CommunicationExtensionDto $dto): CommunicationProvider
    {
        $communicationExtension = CommunicationProvider::create($dto->toArray());

        if (! $communicationExtension) {
            throw new LogicException(__('Can not create communication provider'));
        }

        return $communicationExtension;
    }

    /**
     * Update communication provider.
     *
     * @param  CommunicationProvider  $communicationExtension
     * @param  CommunicationExtensionDto  $dto
     * @return CommunicationProvider
     */
    public function update(CommunicationProvider $communicationExtension, CommunicationExtensionDto $dto): CommunicationProvider
    {
        if (! $communicationExtension->update($dto->toArray())) {
            throw new LogicException(__('Can not update communication provider'));
        }

        return $communicationExtension;
    }

    /**
     * Delete communication provider.
     *
     * @param  CommunicationProvider  $communicationExtension
     * @return bool
     */
    public function delete(CommunicationProvider $communicationExtension): bool
    {
        if (! $communicationExtension->delete()) {
            throw new LogicException(__('Can not delete communication provider'));
        }

        return true;
    }
}
