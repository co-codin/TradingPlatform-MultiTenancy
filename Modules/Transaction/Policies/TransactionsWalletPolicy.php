<?php

declare(strict_types=1);

namespace Modules\Transaction\Policies;

use App\Policies\BasePolicy;
use Modules\Transaction\Enums\TransactionsWalletPermission;
use Modules\Transaction\Models\TransactionsWallet;
use Modules\User\Models\User;

class TransactionsWalletPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(TransactionsWalletPermission::VIEW_TRANSACTION_WALLET);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  TransactionsWallet $transactionsWallet
     * @return bool
     */
    public function view(User $user, TransactionsWallet $transactionsWallet): bool
    {
        return $user->can(TransactionsWalletPermission::VIEW_TRANSACTION_WALLET);
    }

    /**
     * Create policy.
     *
     * @param User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(TransactionsWalletPermission::CREATE_TRANSACTION_WALLET);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  TransactionsWallet  $transactionsWallet
     * @return bool
     */
    public function update(User $user, TransactionsWallet $transactionsWallet): bool
    {
        return $user->can(TransactionsWalletPermission::EDIT_TRANSACTION_WALLET);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  TransactionsWallet  $transactionsWallet
     * @return bool
     */
    public function delete(User $user, TransactionsWallet $transactionsWallet): bool
    {
        return $user->can(TransactionsWalletPermission::DELETE_TRANSACTION_WALLET);
    }
}
