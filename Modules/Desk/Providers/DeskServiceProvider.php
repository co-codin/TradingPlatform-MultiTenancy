<?php

namespace Modules\Desk\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Desk\Models\Desk;
use Modules\Desk\Policies\DeskPolicy;

class DeskServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @inheritdoc
     */
    protected array $policies = [
        Desk::class => DeskPolicy::class,
    ];

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Desk';
    }
}
