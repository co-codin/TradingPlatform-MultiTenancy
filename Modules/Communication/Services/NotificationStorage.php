<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use LogicException;
use Modules\Communication\Dto\NotificationDto;
use Modules\Communication\Models\DatabaseNotification;

final class NotificationStorage
{
    /**
     * Store communication provider.
     *
     * @param  NotificationDto  $dto
     * @return DatabaseNotification
     */
    public function store(NotificationDto $dto): DatabaseNotification
    {
        $notification = DatabaseNotification::create($dto->toArray());

        if (! $notification) {
            throw new LogicException(__('Can not create communication provider'));
        }

        return $notification;
    }

    /**
     * Update communication provider.
     *
     * @param  DatabaseNotification  $notification
     * @param  NotificationDto  $dto
     * @return DatabaseNotification
     */
    public function update(DatabaseNotification $notification, NotificationDto $dto): DatabaseNotification
    {
        if (! $notification->update($dto->toArray())) {
            throw new LogicException(__('Can not update communication provider'));
        }

        return $notification;
    }

    /**
     * Delete communication provider.
     *
     * @param  DatabaseNotification  $notification
     * @return bool
     */
    public function delete(DatabaseNotification $notification): bool
    {
        if (! $notification->delete()) {
            throw new LogicException(__('Can not delete communication provider'));
        }

        return true;
    }
}
