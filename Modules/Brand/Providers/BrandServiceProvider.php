<?php

namespace Modules\Brand\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Brand\Models\Brand;
use Modules\Brand\Policies\BrandPolicy;

class BrandServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @inheritdoc
     */
    protected array $policies = [
        Brand::class => BrandPolicy::class,
    ];

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Brand';
    }
}
