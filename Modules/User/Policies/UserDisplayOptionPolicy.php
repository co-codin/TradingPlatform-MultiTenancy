<?php

declare(strict_types=1);

namespace Modules\User\Policies;

use App\Policies\BasePolicy;
use Modules\User\Enums\UserDisplayOptionPermission;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;

class UserDisplayOptionPolicy extends BasePolicy
{
    /**
     * Create policy.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(UserDisplayOptionPermission::CREATE_USER_DISPLAY_OPTIONS);
    }

    /**
     * Update policy.
     *
     * @param User $user
     * @param int $displayOptionId
     * @return bool
     */
    public function update(User $user, int $displayOptionId): bool
    {
        return $user->displayOptions()->where('id', $displayOptionId)->exists() ||
            $user->can(UserDisplayOptionPermission::EDIT_USER_DISPLAY_OPTIONS);
    }

    /**
     * Delete policy.
     *
     * @param User $user
     * @param int $displayOptionId
     * @return bool
     */
    public function delete(User $user, int $displayOptionId): bool
    {
        return $user->displayOptions()->where('id', $displayOptionId)->exists() ||
            $user->can(UserDisplayOptionPermission::DELETE_USER_DISPLAY_OPTIONS);
    }
}
