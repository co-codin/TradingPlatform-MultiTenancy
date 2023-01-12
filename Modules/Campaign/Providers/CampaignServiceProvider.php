<?php

namespace Modules\Campaign\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Campaign\Models\Campaign;
use Modules\Campaign\Policies\CampaignPolicy;

final class CampaignServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        Campaign::class => CampaignPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Campaign';
    }
}
