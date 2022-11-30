<?php

declare(strict_types=1);

namespace Modules\Config\Policies;

use App\Policies\BasePolicy;
use Modules\Config\Enums\ConfigPermission;
use Modules\Config\Models\Config;
use Modules\User\Models\User;

final class ConfigPolicy extends BasePolicy
{
    /**
     * View any config policy.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(ConfigPermission::VIEW_CONFIGS);
    }

    /**
     * View config policy.
     *
     * @param User $user
     * @param Config $config
     * @return bool
     */
    public function view(User $user, Config $config): bool
    {
        return $user->can(ConfigPermission::VIEW_CONFIGS);
    }

    /**
     * Create config policy.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(ConfigPermission::CREATE_CONFIGS);
    }

    /**
     * Update config policy.
     *
     * @param User $user
     * @param Config $config
     * @return bool
     */
    public function update(User $user, Config $config): bool
    {
        return $user->can(ConfigPermission::EDIT_CONFIGS);
    }

    /**
     * Delete config policy.
     *
     * @param User $user
     * @param Config $config
     * @return bool
     */
    public function delete(User $user, Config $config): bool
    {
        return $user->can(ConfigPermission::DELETE_CONFIGS);
    }
}
