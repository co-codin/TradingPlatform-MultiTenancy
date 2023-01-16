<?php

declare(strict_types=1);

namespace Modules\Campaign\Policies;

use App\Policies\BasePolicy;
use Modules\Campaign\Enums\CampaignTransactionPermission;
use Modules\Campaign\Models\CampaignTransaction;
use Modules\User\Models\User;

class CampaignTransactionPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(CampaignTransactionPermission::VIEW_CAMPAIGN_TRANSACTION);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  CampaignTransaction  $campaignTransaction
     * @return bool
     */
    public function view(User $user, CampaignTransaction $campaignTransaction): bool
    {
        return $user->can(CampaignTransactionPermission::VIEW_CAMPAIGN_TRANSACTION);
    }

    /**
     * Create policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(CampaignTransactionPermission::CREATE_CAMPAIGN_TRANSACTION);
    }

    /**
     * Update policy.
     *
     * @param  User  $user
     * @param  CampaignTransaction  $campaignTransaction
     * @return bool
     */
    public function update(User $user, CampaignTransaction $campaignTransaction): bool
    {
        return $user->can(CampaignTransactionPermission::EDIT_CAMPAIGN_TRANSACTION);
    }
}
