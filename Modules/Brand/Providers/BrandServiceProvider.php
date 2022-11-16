<?php

namespace Modules\Brand\Providers;

use App\Providers\BaseModuleServiceProvider;
use Illuminate\Database\Migrations\Migrator;
use Modules\Brand\Commands\BrandMigrationCommand;
use Modules\Brand\Models\Brand;
use Modules\Brand\Policies\BrandPolicy;

class BrandServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        Brand::class => BrandPolicy::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected array $commands = [
        BrandMigrationCommand::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Brand';
    }

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        parent::boot();

        $this->app->singleton(Migrator::class, function ($app) {
            return $app['migrator'];
        });
    }
}
