<?php

namespace App\Models\Traits;

use App\Services\Tenant\Manager;
use Illuminate\Support\Facades\Config;

trait ForTenant
{
    /**
     * @return bool
     */
    public function isIncrementing(): bool
    {dump($this->getConnectionName() === Manager::TENANT_CONNECTION_NAME);
        return $this->getConnectionName() === Manager::TENANT_CONNECTION_NAME ? false : $this->incrementing;
    }

    /**
     * {@inheritDoc}
     */
    public function getConnectionName(): string
    {
        return app(Manager::class)->hasTenant() ? Manager::TENANT_CONNECTION_NAME : Config::get('database.default');
    }
}
