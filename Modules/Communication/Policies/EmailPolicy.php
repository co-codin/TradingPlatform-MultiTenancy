<?php

declare(strict_types=1);

namespace Modules\Communication\Policies;

use App\Policies\BasePolicy;
use Modules\Communication\Enums\EmailPermission;
use Modules\Communication\Models\Email;
use Modules\User\Models\User;

final class EmailPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(EmailPermission::VIEW_COMMUNICATION_EMAIL);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  Email  $email
     * @return bool
     */
    public function view(User $user, Email $email): bool
    {
        return $user->can(EmailPermission::VIEW_COMMUNICATION_EMAIL);
    }

    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(EmailPermission::CREATE_COMMUNICATION_EMAIL);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  Email  $email
     * @return bool
     */
    public function update(User $user, Email $email): bool
    {
        return $user->can(EmailPermission::EDIT_COMMUNICATION_EMAIL);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  Email  $email
     * @return bool
     */
    public function delete(User $user, Email $email): bool
    {
        return $user->can(EmailPermission::DELETE_COMMUNICATION_EMAIL);
    }
}
