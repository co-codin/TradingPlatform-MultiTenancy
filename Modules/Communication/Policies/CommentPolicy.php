<?php

declare(strict_types=1);

namespace Modules\Communication\Policies;

use App\Policies\BasePolicy;
use Modules\Communication\Enums\CommentPermission;
use Modules\Communication\Models\Comment;
use Modules\User\Models\User;

final class CommentPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(CommentPermission::VIEW_COMMENT);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return bool
     */
    public function view(User $user, Comment $comment): bool
    {
        return $user->can(CommentPermission::VIEW_COMMENT);
    }

    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CommentPermission::CREATE_COMMENT);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return bool
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->can(CommentPermission::EDIT_COMMENT);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  Comment  $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->can(CommentPermission::DELETE_COMMENT);
    }
}
