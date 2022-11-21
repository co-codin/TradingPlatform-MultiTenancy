<?php

namespace App\Services\Tenant;

use Illuminate\Database\Eloquent\Model;

class Manager
{
    /**
     * @var string
     */
    public const TENANT_CONNECTION_NAME = 'tenant';

    /**
     * @var ?Model
     */
    protected ?Model $tenant;

    /**
     * @param ?Model $tenant
     * @return void
     */
    public function setTenant(?Model $tenant = null): void
    {
        $this->tenant = $tenant;
    }

    /**
     * @return Model
     */
    public function getTenant(): Model
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
