<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateBasic
{
    /**
     * The guard factory instance.
     *
     * @var AuthFactory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param AuthFactory $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param Closure $next
     * @return mixed
     *
     * @throws UnauthorizedHttpException
     */
    public function handle(Request $request, Closure $next)
    {
        $this->auth->guard('basic')->basic();

        return $next($request);
    }
}
