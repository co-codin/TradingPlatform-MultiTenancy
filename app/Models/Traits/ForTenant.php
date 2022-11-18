<?php

namespace App\Models\Traits;

use App\Services\Tenant\Manager;
use Illuminate\Support\Facades\Config;

trait ForTenant
{
    /**
     * {@inheritDoc}
     */
    public function getConnectionName(): string
    {
        return app(Manager::class)->hasTenant() ? 'tenant' : Config::get('database.default');
    }
}
