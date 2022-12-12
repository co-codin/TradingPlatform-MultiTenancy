<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\TelephonyProvider\Models\TelephonyProvider;
use Modules\TelephonyProvider\Policies\TelephonyProviderPolicy;

final class TelephonyProviderServiceProvider extends BaseModuleServiceProvider
{
    protected array $policies = [
        TelephonyProvider::class => TelephonyProviderPolicy::class,
    ];

    public function getModuleName(): string
    {
        return 'TelephonyProvider';
    }
}
