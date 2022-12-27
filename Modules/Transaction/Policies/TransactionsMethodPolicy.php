<?php

declare(strict_types=1);

namespace Modules\Transaction\Policies;

use App\Policies\BasePolicy;
use Modules\Transaction\Enums\TransactionsMethodPermission;
use Modules\Transaction\Models\TransactionsMethod;
use Modules\User\Models\User;

class TransactionsMethodPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(TransactionsMethodPermission::VIEW_TRANSACTION_METHOD);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  TransactionsMethod $transactionsMethod
     * @return bool
     */
    public function view(User $user, TransactionsMethod $transactionsMethod): bool
    {
        return $user->can(TransactionsMethodPermission::VIEW_TRANSACTION_METHOD);
    }

    /**
     * Create policy.
     *
     * @param User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(TransactionsMethodPermission::CREATE_TRANSACTION_METHOD);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  TransactionsMethod  $transactionsMethod
     * @return bool
     */
    public function update(User $user, TransactionsMethod $transactionsMethod): bool
    {
        return $user->can(TransactionsMethodPermission::EDIT_TRANSACTION_METHOD);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  TransactionsMethod  $transactionsMethod
     * @return bool
     */
    public function delete(User $user, TransactionsMethod $transactionsMethod): bool
    {
        return $user->can(TransactionsMethodPermission::DELETE_TRANSACTION_METHOD);
    }
}
