<?php

declare(strict_types=1);

namespace Modules\Transaction\Policies;

use App\Policies\BasePolicy;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Transaction\Enums\TransactionPermission;
use Modules\Transaction\Models\Transaction;
use Modules\User\Models\User;

final class TransactionPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  Authenticatable  $user
     * @return bool
     */
    public function viewAny(Authenticatable $user): bool
    {
        return $user->can(TransactionPermission::VIEW_TRANSACTIONS);
    }

    /**
     * View policy.
     *
     * @param  Authenticatable  $user
     * @param  Transaction  $transaction
     * @return bool
     */
    public function view(Authenticatable $user, Transaction $transaction): bool
    {
        return $user->can(TransactionPermission::VIEW_TRANSACTIONS);
    }

    /**
     * Create policy.
     *
     * @param  Authenticatable  $user
     * @return bool
     */
    public function create(Authenticatable $user): bool
    {
        return $user->can(TransactionPermission::CREATE_TRANSACTIONS);
    }

    /**
     * Update policy.
     *
     * @param  Authenticatable  $user
     * @param  Transaction  $transaction
     * @return bool
     */
    public function update(Authenticatable $user, Transaction $transaction): bool
    {
        return $user->can(TransactionPermission::EDIT_TRANSACTIONS);
    }

    /**
     * Delete policy.
     *
     * @param  Authenticatable  $user
     * @param  Transaction  $transaction
     * @return bool
     */
    public function delete(Authenticatable $user, Transaction $transaction): bool
    {
        return $user->can(TransactionPermission::DELETE_TRANSACTIONS);
    }

    /**
     * Export policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function export(User $user): bool
    {
        return $user->can(TransactionPermission::EXPORT_TRANSACTIONS);
    }
}
