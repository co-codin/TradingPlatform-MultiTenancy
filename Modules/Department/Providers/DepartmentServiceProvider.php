<?php

namespace Modules\Department\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Department\Models\Department;
use Modules\Department\Policies\DepartmentPolicy;

class DepartmentServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @inheritdoc
     */
    protected array $policies = [
        Department::class => DepartmentPolicy::class,
    ];

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Department';
    }
}
