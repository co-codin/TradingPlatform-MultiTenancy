<?php

declare(strict_types=1);

namespace Modules\Language\Policies;

use App\Policies\BasePolicy;
use Modules\Language\Enums\LanguagePermission;
use Modules\Language\Models\Language;
use Modules\User\Models\User;

final class LanguagePolicy extends BasePolicy
{
    /**
     * View any language policy.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(LanguagePermission::VIEW_LANGUAGES);
    }

    /**
     * View language policy.
     *
     * @param User $user
     * @param Language $language
     * @return bool
     */
    public function view(User $user, Language $language): bool
    {
        return $user->can(LanguagePermission::VIEW_LANGUAGES);
    }

    /**
     * Create language policy.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(LanguagePermission::CREATE_LANGUAGES);
    }

    /**
     * Update language policy.
     *
     * @param User $user
     * @param Language $language
     * @return bool
     */
    public function update(User $user, Language $language): bool
    {
        return $user->can(LanguagePermission::EDIT_LANGUAGES);
    }

    /**
     * Delete language policy.
     *
     * @param User $user
     * @param Language $language
     * @return bool
     */
    public function delete(User $user, Language $language): bool
    {
        return $user->can(LanguagePermission::DELETE_LANGUAGES);
    }
}
