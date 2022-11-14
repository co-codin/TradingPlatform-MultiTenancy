<?php

namespace Modules\User\Policies;

use App\Policies\BasePolicy;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Department\Models\Department;
use Modules\User\Models\User;

class UserDepartmentPolicy extends BasePolicy
{
    use HandlesAuthorization;
}
