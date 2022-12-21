<?php

declare(strict_types=1);

namespace Modules\Communication\Policies;

use App\Policies\BasePolicy;
use Modules\Communication\Enums\EmailTemplatesPermission;
use Modules\Communication\Models\EmailTemplates;
use Modules\User\Models\User;

final class EmailTemplatesPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(EmailTemplatesPermission::VIEW_COMMUNICATION_EMAIL_TEMPLATE);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  EmailTemplates  $emailtemplates
     * @return bool
     */
    public function view(User $user, EmailTemplates $emailtemplates): bool
    {
        return $user->can(EmailTemplatesPermission::VIEW_COMMUNICATION_EMAIL_TEMPLATE);
    }

    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(EmailTemplatesPermission::CREATE_COMMUNICATION_EMAIL_TEMPLATE);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  EmailTemplates  $emailtemplates
     * @return bool
     */
    public function update(User $user, EmailTemplates $emailtemplates): bool
    {
        return $user->can(EmailTemplatesPermission::EDIT_COMMUNICATION_EMAIL_TEMPLATE);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  EmailTemplates  $emailtemplates
     * @return bool
     */
    public function delete(User $user, EmailTemplates $emailtemplates): bool
    {
        return $user->can(EmailTemplatesPermission::DELETE_COMMUNICATION_EMAIL_TEMPLATE);
    }
}
