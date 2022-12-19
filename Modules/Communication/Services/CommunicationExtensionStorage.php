<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use LogicException;
use Modules\Communication\Dto\CommunicationExtensionDto;
use Modules\Communication\Models\CommunicationExtension;

final class CommunicationExtensionStorage
{
    /**
     * Store communication provider.
     *
     * @param  CommunicationExtensionDto  $dto
     * @return CommunicationExtension
     */
    public function store(CommunicationExtensionDto $dto): CommunicationExtension
    {
        $communicationExtension = CommunicationExtension::create($dto->toArray());

        if (! $communicationExtension) {
            throw new LogicException(__('Can not create communication provider'));
        }

        return $communicationExtension;
    }

    /**
     * Update communication provider.
     *
     * @param  CommunicationExtension  $communicationExtension
     * @param  CommunicationExtensionDto  $dto
     * @return CommunicationExtension
     */
    public function update(
        CommunicationExtension $communicationExtension,
        CommunicationExtensionDto $dto
    ): CommunicationExtension {
        if (! $communicationExtension->update($dto->toArray())) {
            throw new LogicException(__('Can not update communication provider'));
        }

        return $communicationExtension;
    }

    /**
     * Delete communication provider.
     *
     * @param  CommunicationExtension  $communicationExtension
     * @return bool
     */
    public function delete(CommunicationExtension $communicationExtension): bool
    {
        if (! $communicationExtension->delete()) {
            throw new LogicException(__('Can not delete communication provider'));
        }

        return true;
    }
}
