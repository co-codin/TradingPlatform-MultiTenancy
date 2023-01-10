<?php

namespace Modules\Customer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class AffiliateTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $affiliationToken = $request->header('AffiliateToken');

        if (! $request->user()->affiliateToken->contains('token', $affiliationToken)) {
            abort(404, 'AffiliateToken not found');
        }

        return $next($request);
    }
}
