<?php

declare(strict_types=1);

namespace Modules\Config\Providers;

use App\Providers\BaseModuleServiceProvider;

final class ConfigServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritDoc}
     */
    final public function getModuleName(): string
    {
        return 'Config';
    }

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        parent::boot();

        $this->loadMigrationsFrom(module_path($this->getModuleName(), 'Database/Migrations'));
    }
}
