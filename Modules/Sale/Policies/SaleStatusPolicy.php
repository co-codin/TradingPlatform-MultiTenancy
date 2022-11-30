<?php

namespace Modules\Sale\Policies;

use App\Policies\BasePolicy;
use Modules\Sale\Enums\SaleStatusPermission;
use Modules\Sale\Models\SaleStatus;
use Modules\User\Models\User;

final class SaleStatusPolicy extends BasePolicy
{

    /**
     * View any salestatus policy.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(SaleStatusPermission::VIEW_SALESTATUS);
    }

    /**
     * View salestatus policy.
     *
     * @param User $user
     * @param SaleStatus $salestatus
     * @return bool
     */
    public function view(User $user, SaleStatus $salestatus): bool
    {
        return $user->can(SaleStatusPermission::VIEW_SALESTATUS);
    }

    /**
     * Create salestatus policy.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(SaleStatusPermission::CREATE_SALESTATUS);
    }

    /**
     * Update salestatus policy.
     *
     * @param User $user
     * @param SaleStatus $salestatus
     * @return bool
     */
    public function update(User $user, SaleStatus $salestatus): bool
    {
        return $user->can(SaleStatusPermission::EDIT_SALESTATUS);
    }

    /**
     * Delete salestatus policy.
     *
     * @param User $user
     * @param SaleStatus $salestatus
     * @return bool
     */
    public function delete(User $user, SaleStatus $salestatus): bool
    {
        return $user->can(SaleStatusPermission::DELETE_SALESTATUS);
    }
}
