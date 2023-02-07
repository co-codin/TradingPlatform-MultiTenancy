<?php

namespace Modules\ActivityLog\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\ActivityLog\Models\ActivityLog;
use Modules\ActivityLog\Policies\ActivityLogPolicy;

class ActivityLogServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        ActivityLog::class => ActivityLogPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'ActivityLog';
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
