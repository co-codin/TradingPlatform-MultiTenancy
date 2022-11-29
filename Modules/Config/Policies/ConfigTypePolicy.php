<?php

declare(strict_types=1);

namespace Modules\Config\Policies;

use App\Policies\BasePolicy;
use Modules\Config\Enums\ConfigTypePermission;
use Modules\Config\Models\ConfigType;
use Modules\User\Models\User;

final class ConfigTypePolicy extends BasePolicy
{
    /**
     * View any config type policy.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(ConfigTypePermission::VIEW_CONFIG_TYPES);
    }

    /**
     * View config type policy.
     *
     * @param User $user
     * @param ConfigType $configType
     * @return bool
     */
    public function view(User $user, ConfigType $configType): bool
    {
        return $user->can(ConfigTypePermission::VIEW_CONFIG_TYPES);
    }

    /**
     * Create config type policy.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(ConfigTypePermission::CREATE_CONFIG_TYPES);
    }

    /**
     * Update config type policy.
     *
     * @param User $user
     * @param ConfigType $configType
     * @return bool
     */
    public function update(User $user, ConfigType $configType): bool
    {
        return $user->can(ConfigTypePermission::EDIT_CONFIG_TYPES);
    }

    /**
     * Delete config type policy.
     *
     * @param User $user
     * @param ConfigType $configType
     * @return bool
     */
    public function delete(User $user, ConfigType $configType): bool
    {
        return $user->can(ConfigTypePermission::DELETE_CONFIG_TYPES);
    }
}
