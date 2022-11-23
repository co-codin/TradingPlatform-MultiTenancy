<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Contracts\HasTenantDBConnection;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Brand\Enums\BrandPermission;
use Modules\Brand\Events\Tenant\BrandTenantIdentified;
use Modules\Brand\Models\Brand;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class SetTenant
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse|Response|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        BrandTenantIdentified::dispatchIf(
            $request->hasHeader('Tenant'),
            $this->resolveTenant($request),
        );

        return $next($request);
    }

    /**
     * Resolve tenant.
     *
     * @param Request $request
     * @return HasTenantDBConnection
     */
    private function resolveTenant(Request $request): HasTenantDBConnection
    {
        $tenant = Brand::where('slug', $request->header('Tenant'))->first();

        abort_if(! $request->user()->can(BrandPermission::VIEW_BRANDS), ResponseAlias::HTTP_FORBIDDEN);

        return $tenant;
    }
}
