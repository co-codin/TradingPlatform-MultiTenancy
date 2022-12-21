<?php

declare(strict_types=1);

namespace Modules\Customer\Providers;

use App\Providers\BaseModuleServiceProvider;
use App\Services\Auth\PasswordService;
use Modules\Customer\Http\Controllers\Admin\Auth\PasswordController;
use Modules\Customer\Http\Controllers\PasswordController as CustomerPasswordController;
use Modules\Customer\Models\Customer;
use Modules\Customer\Policies\CustomerPolicy;
use Modules\User\Models\User;
use Modules\User\Policies\UserPolicy;

final class CustomerServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        Customer::class => CustomerPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Customer';
    }

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        parent::boot();

        $this->registerViews();
    }

    public function register(): void
    {
        parent::register();

        $this->app->when(PasswordController::class)
            ->needs(PasswordService::class)
            ->give(fn () => new PasswordService(Customer::API_AUTH_GUARD));
        $this->app->when(CustomerPasswordController::class)
            ->needs(PasswordService::class)
            ->give(fn () => new PasswordService(Customer::DEFAULT_AUTH_GUARD));
    }
}
