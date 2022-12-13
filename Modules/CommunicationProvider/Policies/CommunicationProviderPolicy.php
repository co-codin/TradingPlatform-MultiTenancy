<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Policies;

use App\Policies\BasePolicy;
use Modules\CommunicationProvider\Enums\CommunicationProviderPermission;
use Modules\CommunicationProvider\Models\CommunicationProvider;
use Modules\User\Models\User;

final class CommunicationProviderPolicy extends BasePolicy
{
    /**
     * View any communication provider policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(CommunicationProviderPermission::VIEW_TELEPHONY_PROVIDER);
    }

    /**
     * View communication provider policy.
     *
     * @param  User  $user
     * @param  CommunicationProvider  $communicationProvider
     * @return bool
     */
    public function view(User $user, CommunicationProvider $communicationProvider): bool
    {
        return $user->can(CommunicationProviderPermission::VIEW_TELEPHONY_PROVIDER);
    }

    /**
     * Create communication provider policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CommunicationProviderPermission::CREATE_TELEPHONY_PROVIDER);
    }

    /**
     * Update communication provider policy.
     *
     * @param  User  $user
     * @param  CommunicationProvider  $communicationProvider
     * @return bool
     */
    public function update(User $user, CommunicationProvider $communicationProvider): bool
    {
        return $user->can(CommunicationProviderPermission::EDIT_TELEPHONY_PROVIDER);
    }

    /**
     * Delete communication provider policy.
     *
     * @param  User  $user
     * @param  CommunicationProvider  $communicationProvider
     * @return bool
     */
    public function delete(User $user, CommunicationProvider $communicationProvider): bool
    {
        return $user->can(CommunicationProviderPermission::DELETE_TELEPHONY_PROVIDER);
    }
}
