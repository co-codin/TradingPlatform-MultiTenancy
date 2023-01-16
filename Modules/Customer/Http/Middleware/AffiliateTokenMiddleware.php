<?php

namespace Modules\Customer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Campaign\Models\Campaign;
use Modules\Token\Models\Token;
use Modules\User\Models\User;

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
        if (! $token = Token::whereToken($affiliationToken)->first()) {
            abort(404, 'AffiliateToken not found');
        }

        if ($request->post('campaign_id') && ! Campaign::whereAffiliateId($token->user_id)->whereId($request->post('campaign_id'))->exists()) {
            abort(404, 'Campaign not found');
        }

        if ($brand = User::findOrFail($token->user_id)->brands()->first()) {
            $brand->makeCurrent();
        }

        return $next($request);
    }
}
