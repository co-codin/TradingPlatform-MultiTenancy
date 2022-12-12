<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Modules\CommunicationProvider\Models\CommunicationProvider;
use Modules\CommunicationProvider\Policies\CommunicationExtensionPolicy;
use Modules\CommunicationProvider\Policies\CommunicationProviderPolicy;

final class CommunicationProviderServiceProvider extends BaseModuleServiceProvider
{
    protected array $policies = [
        CommunicationProvider::class => CommunicationProviderPolicy::class,
        CommunicationExtension::class => CommunicationExtensionPolicy::class,
    ];

    public function getModuleName(): string
    {
        return 'CommunicationProvider';
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
