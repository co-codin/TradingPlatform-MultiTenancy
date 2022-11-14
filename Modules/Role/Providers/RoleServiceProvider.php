<?php

namespace Modules\Role\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Role\Console\SyncPermissions;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;
use Modules\Role\Policies\PermissionPolicy;
use Modules\Role\Policies\RolePolicy;

class RoleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @inheritdoc
     */
    protected array $policies = [
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
    ];

    /**
     * @inheritdoc
     */
    protected array $commands = [
        SyncPermissions::class,
    ];

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Role';
    }
}
