<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Permission;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Is admin.
     *
     * @param User $user
     * @return bool
     */
    protected function isAdmin(User $user): bool
    {
        return $user->hasRole(DefaultRole::ADMIN);
    }

    /**
     * View any policy.
     *
     * @param User $user
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function viewAny(User $user): bool
    {
        return $this->checkPermissionColumns($user, $this->getPermissionName('VIEW_USERS'));
    }

    /**
     * View policy.
     *
     * @param User $user
     * @param Model $model
     * @return bool
     */
    public function view(User $user, Model $model): bool
    {
        return true;
    }

    /**
     * Check permission columns.
     *
     * @param User $user
     * @param string $permission
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function checkPermissionColumns(User $user, string $permission)
    {
        /** @var Permission $permission */
        $permission = $user->permissions()
            ->where('name', $permission)
            ->first();

        if ($permission->columns()->count() > 0) {
            $fields = request()->get('fields');
            $names = $fields[$this->getFieldName()] ?? [];

            $columns = $permission?->columns()
                ->whereIn('name', $names)
                ->pluck('name');

            if (! empty($columns)) {
                request()->merge([
                    'fields' => [
                        $this->getFieldName() => $columns,
                    ]
                ]);
            }
        }

        return true;
    }

    /**
     * Get model permission class.
     *
     * @return string
     */
    abstract protected function getPermissionName(string $nameEnum): string;

    /**
     * Get field name.
     *
     * @return string
     */
    abstract protected function getFieldName(): string;
}
