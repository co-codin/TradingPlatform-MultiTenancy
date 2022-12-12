<?php

declare(strict_types=1);

namespace Modules\Brand\Providers;

use App\Providers\BaseModuleServiceProvider;
use Illuminate\Database\Migrations\Migrator;
use Modules\Brand\Models\Brand;
use Modules\Brand\Policies\BrandPolicy;

final class BrandServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        Brand::class => BrandPolicy::class,
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

        $this->loadMigrations();
    }
}
