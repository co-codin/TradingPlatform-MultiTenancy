<?php

declare(strict_types=1);

namespace Modules\User\Providers;

use App\Providers\BaseModuleServiceProvider;
use App\Services\Auth\PasswordService;
use Modules\User\Http\Controllers\Admin\AuthController;
use Modules\User\Http\Controllers\Admin\PasswordController;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\Preset;
use Modules\User\Models\User;
use Modules\User\Policies\UserDisplayOptionPolicy;
use Modules\User\Policies\UserPolicy;
use Modules\User\Policies\UserPresetPolicy;

final class UserServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        User::class => UserPolicy::class,
        DisplayOption::class => UserDisplayOptionPolicy::class,
        Preset::class => UserPresetPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'User';
    }

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        parent::boot();

        $this->loadMigrations();
    }

    public function register(): void
    {
        parent::register();

        $this->app->when(PasswordController::class)
            ->needs(PasswordService::class)
            ->give(fn () => new PasswordService(AuthController::GUARD));
    }
}
