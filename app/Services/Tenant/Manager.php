<?php

namespace App\Services\Tenant;

use Illuminate\Database\Eloquent\Model;

class Manager
{
    protected Model $tenant;

    /**
     * @param Model $tenant
     * @return void
     */
    public function setTenant(Model $tenant): void
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
}
