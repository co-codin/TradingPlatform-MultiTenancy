<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Contracts\HasTenantDBConnection;
use Closure;
use Exception;
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
     * @param bool $required
     * @return RedirectResponse|Response|mixed
     * @throws Exception
     */
    public function handle(Request $request, Closure $next, bool $required = false)
    {
        $hasHeader = $request->hasHeader('Tenant');

        if ($required && ! $hasHeader) {
            throw new Exception(__('Tenant header is required.'));
        }

        if ($hasHeader) {
            BrandTenantIdentified::dispatch($this->resolveTenant($request));
        }

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
