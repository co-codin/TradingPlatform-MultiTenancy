<?php

declare(strict_types=1);

namespace Modules\Geo\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Geo\Models\Country;
use Modules\Geo\Policies\CountryPolicy;

final class GeoServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @var array
     */
    protected array $policies = [
        Country::class => CountryPolicy::class,
    ];

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Geo';
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
