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
        User::class => [
            UserPolicy::class,
            UserBanPolicy::class,
        ],
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

    public function registerPolicies(?string $modelKey = null, array $policies = []): void
    {
        $policies = $policies ?: $this->policies;

        foreach ($policies as $key => $value) {
            $key = $modelKey ?? $key;

            switch (true) {
                case is_array($value):
                    $this->registerPolicies($key, $value);
                    break;
                default:
                    Gate::policy($key, $value);
            }
        }
    }
}
