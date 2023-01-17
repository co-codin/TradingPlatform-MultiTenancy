<?php

namespace Modules\Campaign\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Campaign\Models\Campaign;
use Modules\Campaign\Models\CampaignTransaction;
use Modules\Campaign\Policies\CampaignPolicy;
use Modules\Campaign\Policies\CampaignTransactionPolicy;

final class CampaignServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        Campaign::class => CampaignPolicy::class,
        CampaignTransaction::class => CampaignTransactionPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Campaign';
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
