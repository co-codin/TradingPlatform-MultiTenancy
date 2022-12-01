<?php

declare(strict_types=1);

namespace Modules\Customer\Providers;

use App\Providers\BaseModuleServiceProvider;
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
}
