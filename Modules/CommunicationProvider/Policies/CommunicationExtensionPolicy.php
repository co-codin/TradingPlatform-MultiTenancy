<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Policies;

use App\Policies\BasePolicy;
use Modules\CommunicationProvider\Enums\CommunicationExtensionPermission;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Modules\User\Models\User;

final class CommunicationExtensionPolicy extends BasePolicy
{
    /**
     * View any communication provider policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(CommunicationExtensionPermission::VIEW_TELEPHONY_EXTENSION);
    }

    /**
     * View communication provider policy.
     *
     * @param  User  $user
     * @param  CommunicationExtension  $communicationExtension
     * @return bool
     */
    public function view(User $user, CommunicationExtension $communicationExtension): bool
    {
        return $user->can(CommunicationExtensionPermission::VIEW_TELEPHONY_EXTENSION);
    }

    /**
     * Create communication provider policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CommunicationExtensionPermission::CREATE_TELEPHONY_EXTENSION);
    }

    /**
     * Update communication provider policy.
     *
     * @param  User  $user
     * @param  CommunicationExtension  $communicationExtension
     * @return bool
     */
    public function update(User $user, CommunicationExtension $communicationExtension): bool
    {
        return $user->can(CommunicationExtensionPermission::EDIT_TELEPHONY_EXTENSION);
    }

    /**
     * Delete communication provider policy.
     *
     * @param  User  $user
     * @param  CommunicationExtension  $communicationExtension
     * @return bool
     */
    public function delete(User $user, CommunicationExtension $communicationExtension): bool
    {
        return $user->can(CommunicationExtensionPermission::DELETE_TELEPHONY_EXTENSION);
    }
}
