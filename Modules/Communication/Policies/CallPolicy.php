<?php

declare(strict_types=1);

namespace Modules\Communication\Policies;

use App\Policies\BasePolicy;
use Modules\Communication\Enums\CallPermission;
use Modules\Communication\Models\Call;
use Modules\User\Models\User;

final class CallPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(CallPermission::VIEW_COMMUNICATION_CALL);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  Call  $call
     * @return bool
     */
    public function view(User $user, Call $call): bool
    {
        return $user->can(CallPermission::VIEW_COMMUNICATION_CALL);
    }

    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CallPermission::CREATE_COMMUNICATION_CALL);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  Call  $call
     * @return bool
     */
    public function update(User $user, Call $call): bool
    {
        return $user->can(CallPermission::EDIT_COMMUNICATION_CALL);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  Call  $call
     * @return bool
     */
    public function delete(User $user, Call $call): bool
    {
        return $user->can(CallPermission::DELETE_COMMUNICATION_CALL);
    }
}
