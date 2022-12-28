<?php

declare(strict_types=1);

namespace Modules\Communication\Policies;

use App\Policies\BasePolicy;
use Modules\Communication\Enums\NotificationTemplatePermission;
use Modules\Communication\Models\NotificationTemplate;
use Modules\User\Models\User;

final class NotificationTemplatePolicy extends BasePolicy
{
    /**
     * View any notification template policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(NotificationTemplatePermission::VIEW_NOTIFICATION_TEMPLATE);
    }

    /**
     * View notification template policy.
     *
     * @param  User  $user
     * @param  NotificationTemplate  $template
     * @return bool
     */
    public function view(User $user, NotificationTemplate $template): bool
    {
        return $user->can(NotificationTemplatePermission::VIEW_NOTIFICATION_TEMPLATE);
    }

    /**
     * Create notification template policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(NotificationTemplatePermission::CREATE_NOTIFICATION_TEMPLATE);
    }

    /**
     * Update notification template policy.
     *
     * @param  User  $user
     * @param  NotificationTemplate  $template
     * @return bool
     */
    public function update(User $user, NotificationTemplate $template): bool
    {
        return $user->can(NotificationTemplatePermission::EDIT_NOTIFICATION_TEMPLATE);
    }

    /**
     * Delete notification template policy.
     *
     * @param  User  $user
     * @param  NotificationTemplate  $template
     * @return bool
     */
    public function delete(User $user, NotificationTemplate $template): bool
    {
        return $user->can(NotificationTemplatePermission::DELETE_NOTIFICATION_TEMPLATE);
    }

    /**
     * Send notification template policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function send(User $user): bool
    {
        return $user->can(NotificationTemplatePermission::SEND_NOTIFICATION_TEMPLATE);
    }
}
