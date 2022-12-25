<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use Carbon\CarbonImmutable;
use LogicException;
use Modules\Communication\Dto\CommunicationExtensionDto;
use Modules\Communication\Models\CommunicationExtension;

final class CommunicationExtensionStorage
{
    /**
     * Store communication extension.
     *
     * @param  CommunicationExtensionDto  $dto
     * @return CommunicationExtension
     */
    public function store(CommunicationExtensionDto $dto): CommunicationExtension
    {
        $communicationExtension = CommunicationExtension::create($dto->toArray());

        if (! $communicationExtension) {
            throw new LogicException(__('Can not create communication extension'));
        }

        return $communicationExtension;
    }

    /**
     * Update communication extension.
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
            throw new LogicException(__('Can not update communication extension'));
        }

        return $communicationExtension;
    }

    /**
     * Delete communication extension.
     *
     * @param  CommunicationExtension  $communicationExtension
     * @return bool
     */
    public function delete(CommunicationExtension $communicationExtension): bool
    {
        if (! $communicationExtension->delete()) {
            throw new LogicException(__('Can not delete communication extension'));
        }

        return true;
    }

    public function deleteAllByUserId(int $userId): int
    {
        return CommunicationExtension::where('user_id', $userId)->delete();
    }

    public function replaceByUserId(int $userId, array $extensions): bool
    {
        $this->deleteAllByUserId($userId);
        data_fill($extensions, '*.user_id', $userId);
        data_fill($extensions, '*.created_at', CarbonImmutable::now());
        if (! CommunicationExtension::insert($extensions)) {
            throw new LogicException(__('Can not insert communication extensions'));
        }

        return true;
    }
}
