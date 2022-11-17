<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Brand\Models\Brand;

final class BrandDBMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        DB::purge('pgsql');
        $brand = Brand::query()->find($request->header(['brand_id']));
        Config::set('database.connections.pgsql.database', $brand->slug);

        return $next($request);
    }
}
