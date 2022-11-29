<?php

namespace Modules\User\Providers;

use App\Providers\BaseModuleServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;
use Modules\User\Policies\UserBanPolicy;
use Modules\User\Policies\UserDisplayOptionPolicy;
use Modules\User\Policies\UserPolicy;

class UserServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @inheritdoc
     */
    protected array $policies = [
        User::class => UserPolicy::class,
        DisplayOption::class => UserDisplayOptionPolicy::class,
    ];

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'User';
    }

    public function boot(): void
    {
        parent::boot();
        $this->loadMigrationsFrom(module_path($this->getModuleName(), 'Database/Migrations'));
    }
}
