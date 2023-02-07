<?php

declare(strict_types=1);

namespace Modules\ActivityLog\Policies;

use App\Policies\BasePolicy;
use Modules\ActivityLog\Enums\ActivityLogPermission;
use Modules\ActivityLog\Models\ActivityLog;
use Modules\User\Models\User;

class ActivityLogPolicy extends BasePolicy
{
    /**
     * View any policy.
     *
     * @param  User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(ActivityLogPermission::VIEW_ACTIVITY_LOG);
    }

    /**
     * View policy.
     *
     * @param  User  $user
     * @param  ActivityLog  $activityLog
     * @return bool
     */
    public function view(User $user, ActivityLog $activityLog): bool
    {
        return $user->can(ActivityLogPermission::VIEW_ACTIVITY_LOG);
    }
}
