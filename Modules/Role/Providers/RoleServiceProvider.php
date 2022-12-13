<?php

declare(strict_types=1);

namespace Modules\Role\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Role\Console\SyncPermissions;
use Modules\Role\Models\Column;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;
use Modules\Role\Policies\ColumnPolicy;
use Modules\Role\Policies\PermissionPolicy;
use Modules\Role\Policies\RolePolicy;

final class RoleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        Column::class => ColumnPolicy::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected array $commands = [
        SyncPermissions::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Role';
    }
}
