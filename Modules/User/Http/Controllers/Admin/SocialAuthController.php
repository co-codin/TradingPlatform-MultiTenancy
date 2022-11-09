<?php
declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

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

    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }
}
