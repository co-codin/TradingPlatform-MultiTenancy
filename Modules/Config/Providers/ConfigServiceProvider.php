<?php

declare(strict_types=1);

namespace Modules\Config\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Config\Models\Config;
use Modules\Config\Models\ConfigType;
use Modules\Config\Policies\ConfigPolicy;
use Modules\Config\Policies\ConfigTypePolicy;

final class ConfigServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        Config::class => ConfigPolicy::class,
        ConfigType::class => ConfigTypePolicy::class,
    ];

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
    }
}
