<?php

declare(strict_types=1);

namespace Modules\Customer\Policies;

use App\Policies\BasePolicy;
use Modules\Customer\Enums\CustomerChatPermission;
use Modules\Customer\Models\CustomerChatMessage;
use Modules\User\Models\User;

final class CustomerChatPolicy extends BasePolicy
{
    /**
     * View policy.
     *
     * @param  User  $user
     * @param  CustomerChatMessage  $customerChatMessage
     * @return bool
     */
    public function view(User $user, CustomerChatMessage $customerChatMessage): bool
    {
        return $user->can(CustomerChatPermission::VIEW_CUSTOMERS_CHAT);
    }
    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CustomerChatPermission::CREATE_CUSTOMERS_CHAT);
    }
}
