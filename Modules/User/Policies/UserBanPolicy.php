<?php

declare(strict_types=1);

namespace Modules\User\Policies;

use App\Policies\BasePolicy;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;

final class UserBanPolicy extends BasePolicy
{
    /**
     * Ban user policy.
     *
     * @param User $user
     * @return bool
     */
    public function ban(User $user): bool
    {
        return $user->can(UserPermission::BAN_USERS);
    }

    /**
     * Unban user policy.
     *
     * @param User $user
     * @return bool
     */
    public function unban(User $user): bool
    {
        return $user->can(UserPermission::BAN_USERS);
    }
}
