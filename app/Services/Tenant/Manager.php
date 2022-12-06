<?php

declare(strict_types=1);

namespace App\Services\Tenant;

use App\Contracts\HasTenantDBConnection;

final class Manager
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
     * @param  ?HasTenantDBConnection  $tenant
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

    public function escapeTenant(callable $function): void
    {
        $tenant = $this->getTenant();

        $this->setTenant();
        $function();
        $this->setTenant($tenant);
    }
}
