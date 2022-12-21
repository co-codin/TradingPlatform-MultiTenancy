<?php

declare(strict_types=1);

namespace Modules\Communication\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Communication\Models\CommunicationExtension;
use Modules\Communication\Models\CommunicationProvider;
use Modules\Communication\Policies\CommunicationExtensionPolicy;
use Modules\Communication\Policies\CommunicationProviderPolicy;

final class CommunicationServiceProvider extends BaseModuleServiceProvider
{
    protected array $policies = [
        CommunicationProvider::class => CommunicationProviderPolicy::class,
        CommunicationExtension::class => CommunicationExtensionPolicy::class,
        Call::class => CallPolicy::class,
    ];

    public function getModuleName(): string
    {
        return 'Communication';
    }
}
