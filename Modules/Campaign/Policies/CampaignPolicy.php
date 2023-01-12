<?php

declare(strict_types=1);

namespace Modules\Campaign\Policies;

use App\Policies\BasePolicy;
use Modules\Campaign\Enums\CampaignPermission;
use Modules\Campaign\Models\Campaign;
use Modules\User\Models\User;

class CampaignPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(CampaignPermission::VIEW_CAMPAIGN);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  Campaign $campaign
     * @return bool
     */
    public function view(User $user, Campaign $campaign): bool
    {
        return $user->can(CampaignPermission::VIEW_CAMPAIGN);
    }

    /**
     * Create policy.
     *
     * @param User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CampaignPermission::CREATE_CAMPAIGN);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  Campaign  $campaign
     * @return bool
     */
    public function update(User $user, Campaign $campaign): bool
    {
        return $user->can(CampaignPermission::EDIT_CAMPAIGN);
    }

    /**
     * Delete policy.
     *
     * @param  User  $user
     * @param  Campaign  $campaign
     * @return bool
     */
    public function delete(User $user, Campaign $campaign): bool
    {
        return $user->can(CampaignPermission::DELETE_CAMPAIGN);
    }
}
