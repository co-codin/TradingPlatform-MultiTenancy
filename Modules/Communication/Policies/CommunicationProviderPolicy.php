<?php

declare(strict_types=1);

namespace Modules\Communication\Policies;

use App\Policies\BasePolicy;
use Modules\Communication\Enums\CommunicationProviderPermission;
use Modules\Communication\Models\CommunicationProvider;
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
        return $user->can(CommunicationProviderPermission::VIEW_COMMUNICATION_PROVIDER);
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
        return $user->can(CommunicationProviderPermission::VIEW_COMMUNICATION_PROVIDER);
    }

    /**
     * Create communication provider policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CommunicationProviderPermission::CREATE_COMMUNICATION_PROVIDER);
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
        return $user->can(CommunicationProviderPermission::EDIT_COMMUNICATION_PROVIDER);
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
        return $user->can(CommunicationProviderPermission::DELETE_COMMUNICATION_PROVIDER);
    }
}
