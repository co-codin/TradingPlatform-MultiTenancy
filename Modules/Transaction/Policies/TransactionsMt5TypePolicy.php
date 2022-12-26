<?php

declare(strict_types=1);

namespace Modules\Transaction\Policies;

use App\Policies\BasePolicy;
use Modules\Transaction\Enums\TransactionsMt5TypePermission;
use Modules\Transaction\Models\TransactionsMt5Type;
use Modules\User\Models\User;

class TransactionsMt5TypePolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(TransactionsMt5TypePermission::VIEW_TRANSACTION_MT5_TYPE);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  TransactionsMt5Type $transactionsMt5Type
     * @return bool
     */
    public function view(User $user, TransactionsMt5Type $transactionsMt5Type): bool
    {
        return $user->can(TransactionsMt5TypePermission::VIEW_TRANSACTION_MT5_TYPE);
    }

    /**
     * Create policy.
     *
     * @param User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(TransactionsMt5TypePermission::CREATE_TRANSACTION_MT5_TYPE);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  TransactionsMt5Type  $transactionsMt5Type
     * @return bool
     */
    public function update(User $user, TransactionsMt5Type $transactionsMt5Type): bool
    {
        return $user->can(TransactionsMt5TypePermission::EDIT_TRANSACTION_MT5_TYPE);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  TransactionsMt5Type  $transactionsMt5Type
     * @return bool
     */
    public function delete(User $user, TransactionsMt5Type $transactionsMt5Type): bool
    {
        return $user->can(TransactionsMt5TypePermission::DELETE_TRANSACTION_MT5_TYPE);
    }
}
