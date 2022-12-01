<?php

declare(strict_types=1);

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
        return $user->can(SaleStatusPermission::VIEW_SALE_STATUSES);
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
        return $user->can(SaleStatusPermission::VIEW_SALE_STATUSES);
    }

    /**
     * Create salestatus policy.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(SaleStatusPermission::CREATE_SALE_STATUSES);
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
        return $user->can(SaleStatusPermission::EDIT_SALE_STATUSES);
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
        return $user->can(SaleStatusPermission::DELETE_SALE_STATUSES);
    }
}
