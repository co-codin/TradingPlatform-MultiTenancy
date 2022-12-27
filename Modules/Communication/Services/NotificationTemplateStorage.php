<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use LogicException;
use Modules\Communication\Dto\NotificationTemplateDto;
use Modules\Communication\Models\NotificationTemplate;

final class NotificationTemplateStorage
{
    /**
     * Store communication provider.
     *
     * @param  NotificationTemplateDto  $dto
     * @return NotificationTemplate
     */
    public function store(NotificationTemplateDto $dto): NotificationTemplate
    {
        $notification = NotificationTemplate::create($dto->toArray());

        if (! $notification) {
            throw new LogicException(__('Can not create communication provider'));
        }

        return $notification;
    }

    /**
     * Update communication provider.
     *
     * @param  NotificationTemplate  $notification
     * @param  NotificationTemplateDto  $dto
     * @return NotificationTemplate
     */
    public function update(NotificationTemplate $notification, NotificationTemplateDto $dto): NotificationTemplate
    {
        if (! $notification->update($dto->toArray())) {
            throw new LogicException(__('Can not update communication provider'));
        }

        return $notification;
    }

    /**
     * Delete communication provider.
     *
     * @param  NotificationTemplate  $notification
     * @return bool
     */
    public function delete(NotificationTemplate $notification): bool
    {
        if (! $notification->delete()) {
            throw new LogicException(__('Can not delete communication provider'));
        }

        return true;
    }
}
