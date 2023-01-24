<?php

declare(strict_types=1);

namespace Modules\Role\Policies;

use App\Policies\BasePolicy;
use Modules\Role\Enums\ModelPermission;
use Modules\User\Models\User;

final class ModelPolicy extends BasePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->can(ModelPermission::VIEW_MODELS);
    }
}
