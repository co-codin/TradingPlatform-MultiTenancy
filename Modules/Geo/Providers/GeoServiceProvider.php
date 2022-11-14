<?php

namespace Modules\Geo\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Geo\Models\Country;
use Modules\Geo\Policies\CountryPolicy;

class GeoServiceProvider extends BaseModuleServiceProvider
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
}
