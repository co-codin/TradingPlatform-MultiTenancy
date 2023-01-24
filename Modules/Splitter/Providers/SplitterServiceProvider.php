<?php

namespace Modules\Splitter\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Splitter\Models\Splitter;
use Modules\Splitter\Policies\SplitterPolicy;

class SplitterServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        Splitter::class => SplitterPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Splitter';
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
