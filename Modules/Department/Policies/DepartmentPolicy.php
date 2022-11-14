<?php

namespace Modules\Department\Policies;

use App\Policies\BasePolicy;
use Modules\Department\Enums\DepartmentPermission;
use Modules\Department\Models\Department;
use Modules\User\Models\User;

class DepartmentPolicy extends BasePolicy
{
    /**
     * View any department policy.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can(DepartmentPermission::VIEW_DEPARTMENTS);
    }

    /**
     * View department policy.
     *
     * @param User $user
     * @param Department $department
     * @return bool
     */
    public function view(User $user, Department $department): bool
    {
        return $user->can(DepartmentPermission::VIEW_DEPARTMENTS);
    }

    /**
     * Create department policy.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(DepartmentPermission::CREATE_DEPARTMENTS);
    }

    /**
     * Update department policy.
     *
     * @param User $user
     * @param Department $department
     * @return bool
     */
    public function update(User $user, Department $department): bool
    {
        return $user->can(DepartmentPermission::EDIT_DEPARTMENTS);
    }

    /**
     * Delete department policy.
     *
     * @param User $user
     * @param Department $department
     * @return bool
     */
    public function delete(User $user, Department $department): bool
    {
        return $user->can(DepartmentPermission::DELETE_DEPARTMENTS);
    }
}
