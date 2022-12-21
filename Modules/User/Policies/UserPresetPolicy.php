<?php

declare(strict_types=1);

namespace Modules\User\Policies;

use App\Policies\BasePolicy;
use Modules\User\Enums\UserPresetPermission;
use Modules\User\Models\Preset;
use Modules\User\Models\User;

class UserPresetPolicy extends BasePolicy
{
    /**
     * View policy.
     *
     * @param  User  $user
     * @param  Preset  $preset
     * @return bool
     */
    public function view(User $user, Preset $preset): bool
    {
        return $preset->user_id === $user->id ||
            $user->can(UserPresetPermission::VIEW_USER_PRESETS);
    }

    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(UserPresetPermission::CREATE_USER_PRESETS);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  Preset  $preset
     * @return bool
     */
    public function update(User $user, Preset $preset): bool
    {
        return $preset->user_id === $user->id ||
            $user->can(UserPresetPermission::EDIT_USER_PRESETS);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  Preset  $preset
     * @return bool
     */
    public function delete(User $user, Preset $preset): bool
    {
        return $preset->user_id === $user->id ||
            $user->can(UserPresetPermission::DELETE_USER_PRESETS);
    }
}
