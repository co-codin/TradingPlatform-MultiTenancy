<?php

declare(strict_types=1);

namespace Modules\Sale\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Sale\Models\SaleStatus;
use Modules\Sale\Policies\SaleStatusPolicy;

final class SaleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @var array
     */
    protected array $policies = [
        SaleStatus::class => SaleStatusPolicy::class,
    ];
    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Sale';
    }
}
