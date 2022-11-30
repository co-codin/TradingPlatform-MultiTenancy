<?php

namespace Modules\Sale\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Sale\Models\SaleStatus;
use Modules\Sale\Policies\SalePolicy;

class SaleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @var array
     */
    protected array $policies = [
        SaleStatus::class => SalePolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Sale';
    }
}
