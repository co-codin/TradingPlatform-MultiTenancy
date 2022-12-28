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
     * View any notification policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(NotificationTemplatePermission::VIEW_NOTIFICATION_TEMPLATE);
    }

    /**
     * View notification policy.
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
     * Create notification policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(NotificationTemplatePermission::CREATE_NOTIFICATION_TEMPLATE);
    }

    /**
     * Update notification policy.
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
     * Delete notification policy.
     *
     * @param  User  $user
     * @param  NotificationTemplate  $template
     * @return bool
     */
    public function delete(User $user, NotificationTemplate $template): bool
    {
        return $user->can(NotificationTemplatePermission::DELETE_NOTIFICATION_TEMPLATE);
    }
}
