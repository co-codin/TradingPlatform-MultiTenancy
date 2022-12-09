<?php

namespace App\Tenant;

use Illuminate\Http\Request;
use Exception;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder;

class DomainTenantFinder extends TenantFinder
{
    use UsesTenantModel;

    public function findForRequest(Request $request): ?Tenant
    {
        $domain = $request->header('tenant');

        if (!$domain) {
            throw new Exception(__('Tenant header is required.'));
        }

        return $this->getTenantModel()::whereDomain($domain)->first();
    }
}
