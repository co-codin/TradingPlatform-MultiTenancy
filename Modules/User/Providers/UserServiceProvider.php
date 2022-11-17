<?php

namespace Modules\User\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;
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
}
