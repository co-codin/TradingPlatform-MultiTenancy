<?php

declare(strict_types=1);

namespace Modules\Transaction\Policies;

use App\Policies\BasePolicy;
use Modules\Transaction\Enums\WalletPermission;
use Modules\Transaction\Models\Wallet;
use Modules\User\Models\User;

class WalletPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(WalletPermission::VIEW_WALLET);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  Wallet $wallet
     * @return bool
     */
    public function view(User $user, Wallet $wallet): bool
    {
        return $user->can(WalletPermission::VIEW_WALLET);
    }

    /**
     * Create policy.
     *
     * @param User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(WalletPermission::CREATE_WALLET);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  Wallet  $wallet
     * @return bool
     */
    public function update(User $user, Wallet $wallet): bool
    {
        return $user->can(WalletPermission::EDIT_WALLET);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  Wallet  $wallet
     * @return bool
     */
    public function delete(User $user, Wallet $wallet): bool
    {
        return $user->can(WalletPermission::DELETE_WALLET);
    }
}
