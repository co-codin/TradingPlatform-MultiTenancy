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
        $template = NotificationTemplate::create([
            'data' => [
                'subject' => $dto->subject,
                'text' => $dto->text,
            ],
        ]);

        if (! $template) {
            throw new LogicException(__('Can not create notification template'));
        }

        return $template;
    }

    /**
     * Update notification template.
     *
     * @param  NotificationTemplate  $template
     * @param  NotificationTemplateDto  $dto
     * @return NotificationTemplate
     */
    public function update(NotificationTemplate $template, NotificationTemplateDto $dto): NotificationTemplate
    {
        if (! $template->update([
            'data' => [
                'subject' => $dto->subject ?? $template->data['subject'],
                'text' => $dto->text ?? $template->data['text'],
            ],
        ])) {
            throw new LogicException(__('Can not update notification template'));
        }

        return $template;
    }

    /**
     * Delete notification template.
     *
     * @param  NotificationTemplate  $template
     * @return bool
     */
    public function delete(NotificationTemplate $template): bool
    {
        if (! $template->delete()) {
            throw new LogicException(__('Can not delete notification template'));
        }

        return true;
    }
}
