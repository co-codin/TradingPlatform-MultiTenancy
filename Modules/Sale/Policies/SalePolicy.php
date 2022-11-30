<?php

namespace Modules\Sale\Policies;

use App\Policies\BasePolicy;
use Modules\Sale\Enums\SalePermission;
use Modules\Sale\Models\SaleStatus;
use Modules\User\Models\User;

final class SalePolicy extends BasePolicy
{

    /**
     * View any sale policy.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(SalePermission::VIEW_SALE);
    }

    /**
     * View sale policy.
     *
     * @param User $user
     * @param SaleStatus $sale
     * @return bool
     */
    public function view(User $user, SaleStatus $sale): bool
    {
        return $user->can(SalePermission::VIEW_SALE);
    }

    /**
     * Create sale policy.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(SalePermission::CREATE_SALE);
    }

    /**
     * Update sale policy.
     *
     * @param User $user
     * @param SaleStatus $sale
     * @return bool
     */
    public function update(User $user, SaleStatus $sale): bool
    {
        return $user->can(SalePermission::EDIT_SALE);
    }

    /**
     * Delete sale policy.
     *
     * @param User $user
     * @param SaleStatus $sale
     * @return bool
     */
    public function delete(User $user, SaleStatus $sale): bool
    {
        return $user->can(SalePermission::DELETE_SALE);
    }
}
