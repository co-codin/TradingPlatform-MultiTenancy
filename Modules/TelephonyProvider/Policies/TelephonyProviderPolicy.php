<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Policies;

use App\Policies\BasePolicy;
use Modules\TelephonyProvider\Enums\TelephonyProviderPermission;
use Modules\TelephonyProvider\Models\TelephonyProvider;
use Modules\User\Models\User;

final class TelephonyProviderPolicy extends BasePolicy
{

    /**
     * View any telephony provider policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(TelephonyProviderPermission::VIEW_TELEPHONY_PROVIDER);
    }

    /**
     * View telephony provider policy.
     *
     * @param  User  $user
     * @param  TelephonyProvider  $telephonyProvider
     * @return bool
     */
    public function view(User $user, TelephonyProvider $telephonyProvider): bool
    {
        return $user->can(TelephonyProviderPermission::VIEW_TELEPHONY_PROVIDER);
    }

    /**
     * Create telephony provider policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(TelephonyProviderPermission::CREATE_TELEPHONY_PROVIDER);
    }

    /**
     * Update telephony provider policy.
     *
     * @param  User  $user
     * @param  TelephonyProvider  $telephonyProvider
     * @return bool
     */
    public function update(User $user, TelephonyProvider $telephonyProvider): bool
    {
        return $user->can(TelephonyProviderPermission::EDIT_TELEPHONY_PROVIDER);
    }

    /**
     * Delete telephony provider policy.
     *
     * @param  User  $user
     * @param  TelephonyProvider  $telephonyProvider
     * @return bool
     */
    public function delete(User $user, TelephonyProvider $telephonyProvider): bool
    {
        return $user->can(TelephonyProviderPermission::DELETE_TELEPHONY_PROVIDER);
    }
}
