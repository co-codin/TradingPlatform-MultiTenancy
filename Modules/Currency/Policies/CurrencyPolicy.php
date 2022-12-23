<?php

declare(strict_types=1);

namespace Modules\Geo\Policies;

use App\Policies\BasePolicy;
use Modules\Currency\Models\Currency;
use Modules\Currency\Enums\CurrencyPermission;
use Modules\User\Models\User;

final class CurrencyPolicy extends BasePolicy
{
    /**
     * View policy.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(CurrencyPermission::VIEW_CURRENCIES);
    }

    /**
     * View policy.
     *
     * @param User $user
     * @param Currency $currency
     * @return bool
     */
    public function view(User $user, Currency $currency): bool
    {
        return $user->can(CurrencyPermission::VIEW_CURRENCIES);
    }

    /**
     * Create policy.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CurrencyPermission::CREATE_CURRENCIES);
    }

    /**
     * Update policy.
     *
     * @param User $user
     * @param Currency $currency
     * @return bool
     */
    public function update(User $user, Currency $currency): bool
    {
        return $user->can(CurrencyPermission::EDIT_CURRENCIES);
    }

    /**
     * Delete policy.
     *
     * @param User $user
     * @param Currency $currency
     * @return bool
     */
    public function delete(User $user, Currency $currency): bool
    {
        return $user->can(CurrencyPermission::DELETE_CURRENCIES);
    }
}
