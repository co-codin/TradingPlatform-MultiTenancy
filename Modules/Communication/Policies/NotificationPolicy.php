<?php

declare(strict_types=1);

namespace Modules\Communication\Policies;

use App\Policies\BasePolicy;
use Modules\Communication\Enums\NotificationPermission;
use Modules\Communication\Models\DatabaseNotification;
use Modules\User\Models\User;

final class NotificationPolicy extends BasePolicy
{
    /**
     * View any notification policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(NotificationPermission::VIEW_NOTIFICATION);
    }

    /**
     * View notification policy.
     *
     * @param  User  $user
     * @param  DatabaseNotification  $notification
     * @return bool
     */
    public function view(User $user, DatabaseNotification $notification): bool
    {
        return $user->can(NotificationPermission::VIEW_NOTIFICATION);
    }

    /**
     * Create notification policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(NotificationPermission::CREATE_NOTIFICATION);
    }

    /**
     * Update notification policy.
     *
     * @param  User  $user
     * @param  DatabaseNotification  $notification
     * @return bool
     */
    public function update(User $user, DatabaseNotification $notification): bool
    {
        return $user->can(NotificationPermission::EDIT_NOTIFICATION);
    }

    /**
     * Delete notification policy.
     *
     * @param  User  $user
     * @param  DatabaseNotification  $notification
     * @return bool
     */
    public function delete(User $user, DatabaseNotification $notification): bool
    {
        return $user->can(NotificationPermission::DELETE_NOTIFICATION);
    }

    /**
     * Send notification policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function send(User $user): bool
    {
        return $user->can(NotificationPermission::SEND_NOTIFICATION);
    }
}
