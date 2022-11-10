<?php
declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Services\SocialAuthService;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;

class SocialAuthController extends Controller
{
    protected SocialAuthService $service;

    public function __construct(SocialAuthService $service)
    {
    }

    /**
     * @throws ValidationException
     */
    public function callback(string $provider): array
    {
        $user = $this->service->authenticate($provider);

        if ($user === null) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'user' => new AuthUserResource($user),
            'token' => $user->createToken('api')->plainTextToken,
        ];
    }

    /**
     * @OA\Get(
     *     path="/admin/auth/login/{provider}",
     *     tags={"Auth"},
     *     summary="OAuth provider login endpoint",
     *     @OA\Parameter(
     *          name="provider",
     *          in="path",
     *          description="OAuth provider name",
     *          @OA\Schema (
     *              type="string",
     *              example="google"
     *          )
     *     ),
     *     @OA\Response(
     *          response=302,
     *          description="Redirects to Google auth"
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *     )
     * )
     *
     * @param string $provider
     * @return RedirectResponse
     */
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }
}
