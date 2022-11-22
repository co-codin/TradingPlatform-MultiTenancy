<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Services\SocialAuthService;
use Modules\User\Services\UserStorage;
use OpenApi\Annotations as OA;
use Throwable;

final class SocialAuthController extends Controller
{
    public function __construct(
        protected UserStorage $userStorage,
    ) {
    }

    /**
     * @param  Request  $request
     * @param  string  $provider
     * @param  SocialAuthService  $service
     * @return Application|RedirectResponse|Redirector
     *
     * @throws Exception
     */
    public function callback(Request $request, string $provider, SocialAuthService $service)
    {
        try {
            $service->setProvider($provider);
            $user = $service->findUser();

            if (! $user) {
                throw ValidationException::withMessages([
                    'email' => 'The provided credentials are incorrect.',
                ]);
            }

            if ($user->banned_at) {
                throw ValidationException::withMessages([
                    'banned' => 'You have been banned',
                ]);
            }

            Auth::login($user, session('remember_me', false));
            $request->session()->regenerate();
            $this->userStorage->update($user, ['last_login' => CarbonImmutable::now()]);
        } catch (Throwable $e) {
            if ($e instanceof ValidationException) {
                $code = $e->status;
                $message = [
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ];
            }

            $result = [
                'code' => $code ?? Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $message ?? $e->getMessage(),
            ];
        }

        return redirect(session('redirect_url') . (isset($result) ? '?' . Arr::query($result) : null));
    }

    /**
     * @OA\Get(
     *     path="/admin/auth/login/{provider}",
     *     tags={"Auth"},
     *     summary="OAuth provider login endpoint",
     *     @OA\Parameter(
     *          name="provider",
     *          in="path",
     *          required=true,
     *          description="OAuth provider name",
     *          @OA\Schema (
     *              type="string",
     *              example="google"
     *          )
     *     ),
     *     @OA\Parameter(
     *          name="remember_me",
     *          in="query",
     *          description="Remember user",
     *          @OA\Schema (
     *              type="integer",
     *              enum={"1", "0"}
     *          ),
     *     ),
     *     @OA\Parameter(
     *          name="redirect_url",
     *          in="query",
     *          required=true,
     *          description="URL where the user will be redirected after authentication",
     *          @OA\Schema (type="string")
     *     ),
     *     @OA\Response(
     *          response=302,
     *          description="Redirects to auth page",
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * @param  Request  $request
     * @param  string  $provider
     * @return RedirectResponse
     */
    public function redirect(Request $request, string $provider): RedirectResponse
    {
        $request->validate(['remember_me' => 'bool', 'redirect_url' => 'required|url']);
        session([
            'remember_me' => (bool) $request->query('remember_me', false),
            'redirect_url' => $request->query('redirect_url'),
        ]);

        return Socialite::driver($provider)->redirect();
    }
}
