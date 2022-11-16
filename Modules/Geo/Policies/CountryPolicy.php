<?php

declare(strict_types=1);

namespace Modules\Geo\Policies;

use App\Policies\BasePolicy;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Modules\User\Models\User;

final class CountryPolicy extends BasePolicy
{
    /**
     * View any country policy.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(CountryPermission::VIEW_COUNTRIES);
    }

    /**
     * View country policy.
     *
     * @param User $user
     * @param Country $country
     * @return bool
     */
    public function view(User $user, Country $country): bool
    {
        return $user->can(CountryPermission::VIEW_COUNTRIES);
    }

    /**
     * Create country policy.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CountryPermission::CREATE_COUNTRIES);
    }

    /**
     * Update country policy.
     *
     * @param User $user
     * @param Country $country
     * @return bool
     */
    public function update(User $user, Country $country): bool
    {
        return $user->can(CountryPermission::EDIT_COUNTRIES);
    }

    /**
     * Delete country policy.
     *
     * @param User $user
     * @param Country $country
     * @return bool
     */
    public function delete(User $user, Country $country): bool
    {
        return $user->can(CountryPermission::DELETE_COUNTRIES);
    }
}
