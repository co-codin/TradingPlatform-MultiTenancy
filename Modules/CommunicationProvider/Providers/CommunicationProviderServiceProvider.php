<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\CommunicationProvider\Models\CommunicationProvider;
use Modules\CommunicationProvider\Policies\CommunicationProviderPolicy;

final class CommunicationProviderServiceProvider extends BaseModuleServiceProvider
{
    protected array $policies = [
        CommunicationProvider::class => CommunicationProviderPolicy::class,
    ];

    public function getModuleName(): string
    {
        return 'CommunicationProvider';
    }
}
