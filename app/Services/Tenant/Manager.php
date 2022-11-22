<?php

namespace App\Services\Tenant;

use App\Contracts\HasTenantDBConnection;

class Manager
{
    /**
     * @var string
     */
    public const TENANT_CONNECTION_NAME = 'tenant';

    /**
     * @var ?HasTenantDBConnection
     */
    protected ?HasTenantDBConnection $tenant;

    /**
     * @param ?HasTenantDBConnection $tenant
     * @return void
     */
    public function setTenant(?HasTenantDBConnection $tenant = null): void
    {
        $this->tenant = $tenant;
    }

    /**
     * @return HasTenantDBConnection
     */
    public function getTenant(): HasTenantDBConnection
    {
        return $this->tenant;
    }

    /**
     * @return bool
     */
    public function hasTenant(): bool
    {
        return isset($this->tenant);
    }

    public function escapeTenant(callable $function)
    {
        $tenant = $this->getTenant();

        $this->setTenant();
        call_user_func($function);
        $this->setTenant($tenant);
    }
}
