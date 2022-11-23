<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class JsonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    final public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $request->headers->set('Accept', 'application/json');

        $response = $next($request);

        return match (true) {
            boolval(config('app.debug')) => $response,
            $response->isSuccessful() => $response,
            $response->isNotFound() => new Response(__('Not found.'), 404),
            default => new Response(__('Internal server error'), 500),
        };
    }
}
