<?php

declare(strict_types=1);

namespace Modules\Currency\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Currency\Models\Currency;
use Modules\Currency\Policies\CurrencyPolicy;

final class CurrencyServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $provides = [
        Currency::class => CurrencyPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Currency';
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
