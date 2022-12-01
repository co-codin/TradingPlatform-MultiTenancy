<?php

namespace Modules\Department\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Department\Models\Department;
use Modules\Department\Policies\DepartmentPolicy;
use Modules\User\Models\User;
use Modules\User\Policies\UserPolicy;

class DepartmentServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        Department::class => DepartmentPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Department';
    }
}
