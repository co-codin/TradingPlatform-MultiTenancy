<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Modules\Customer\Models\Customer;
use Modules\Role\Enums\DefaultRole;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            return $user->hasRole(DefaultRole::ADMIN) ? true : null;
        });

        ResetPassword::createUrlUsing(function ($user, string $token) {
            $prefix = $user instanceof Customer ? '/customer' : null;

            return config('app.frontend_url') . "{$prefix}/auth/new-password?" . Arr::query([
                'token' => $token,
                'email' => $user->getEmailForPasswordReset(),
            ]);
        });
    }
}
