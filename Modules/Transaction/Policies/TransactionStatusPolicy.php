<?php

declare(strict_types=1);

namespace Modules\Transaction\Policies;

use App\Policies\BasePolicy;
use Modules\Transaction\Enums\TransactionStatusPermission;
use Modules\Transaction\Models\TransactionStatus;
use Modules\User\Models\User;

class TransactionStatusPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(TransactionStatusPermission::VIEW_TRANSACTION_STATUSES);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  TransactionStatus  $transactionStatus
     * @return bool
     */
    public function view(User $user, TransactionStatus $transactionStatus): bool
    {
        return $user->can(TransactionStatusPermission::VIEW_TRANSACTION_STATUSES);
    }

    /**
     * Create policy.
     *
     * @param User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(TransactionStatusPermission::CREATE_TRANSACTION_STATUSES);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  TransactionStatus  $transactionStatus
     * @return bool
     */
    public function update(User $user, TransactionStatus $transactionStatus): bool
    {
        return $user->can(TransactionStatusPermission::EDIT_TRANSACTION_STATUSES);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  TransactionStatus  $transactionStatus
     * @return bool
     */
    public function delete(User $user, TransactionStatus $transactionStatus): bool
    {
        return $user->can(TransactionStatusPermission::DELETE_TRANSACTION_STATUSES);
    }
}
