<?php

declare(strict_types=1);

namespace Modules\User\Policies;

use App\Policies\BasePolicy;
use Modules\User\Enums\UserDisplayOptionPermission;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;

class UserDisplayOptionPolicy extends BasePolicy
{
    /**
     * View policy.
     *
     * @param  User  $user
     * @param  DisplayOption  $displayOption
     * @return bool
     */
    public function view(User $user, DisplayOption $displayOption): bool
    {
        return $displayOption->user_id === $user->id ||
            $user->can(UserDisplayOptionPermission::VIEW_USER_DISPLAY_OPTIONS);
    }

    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(UserDisplayOptionPermission::CREATE_USER_DISPLAY_OPTIONS);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  DisplayOption  $displayOption
     * @return bool
     */
    public function update(User $user, DisplayOption $displayOption): bool
    {
        return $displayOption->user_id === $user->id ||
            $user->can(UserDisplayOptionPermission::EDIT_USER_DISPLAY_OPTIONS);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  DisplayOption  $displayOption
     * @return bool
     */
    public function delete(User $user, DisplayOption $displayOption): bool
    {
        return $displayOption->user_id === $user->id ||
            $user->can(UserDisplayOptionPermission::DELETE_USER_DISPLAY_OPTIONS);
    }
}
