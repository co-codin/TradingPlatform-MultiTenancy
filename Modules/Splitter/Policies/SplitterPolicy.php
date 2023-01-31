<?php

declare(strict_types=1);

namespace Modules\Splitter\Policies;

use App\Policies\BasePolicy;
use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Modules\User\Models\User;

class SplitterPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(SplitterPermission::VIEW_SPLITTER);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  Splitter  $splitter
     * @return bool
     */
    public function view(User $user, Splitter $splitter): bool
    {
        return $user->can(SplitterPermission::VIEW_SPLITTER);
    }

    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(SplitterPermission::CREATE_SPLITTER);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  Splitter  $splitter
     * @return bool
     */
    public function update(User $user, Splitter $splitter): bool
    {
        return $user->can(SplitterPermission::EDIT_SPLITTER);
    }

    /**
     * Update positions policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function updatePositions(User $user): bool
    {
        return $user->can(SplitterPermission::EDIT_SPLITTER_POSITIONS);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  Splitter  $splitter
     * @return bool
     */
    public function delete(User $user, Splitter $splitter): bool
    {
        return $user->can(SplitterPermission::DELETE_SPLITTER);
    }
}
