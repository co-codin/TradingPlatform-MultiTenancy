<?php

declare(strict_types=1);

namespace Modules\Communication\Policies;

use App\Policies\BasePolicy;
use Modules\Communication\Enums\ChatPermission;
use Modules\Communication\Models\ChatMessage;
use Modules\User\Models\User;

final class ChatPolicy extends BasePolicy
{
    /**
     * View policy.
     *
     * @param  User  $user
     * @param  ChatMessage  $chatMessage
     * @return bool
     */
    public function view(User $user, ChatMessage $chatMessage): bool
    {
        return $user->can(ChatPermission::VIEW_COMMUNICATION_CHAT);
    }
    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(ChatPermission::CREATE_COMMUNICATION_CHAT);
    }
}
