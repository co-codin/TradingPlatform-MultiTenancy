<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Requests\LoginAuthRequest;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Models\User;
use Modules\User\Services\UserStorage;
use OpenApi\Annotations as OA;

final class AuthController extends Controller
{
    public function __construct(
        protected UserStorage $userStorage,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/admin/auth/login",
     *     tags={"Auth"},
     *     summary="Auth User",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={
     *                      "email",
     *                      "password",
     *                  },
     *                  type="object",
     *                  @OA\Property(property="email", type="string", format="email"),
     *                  @OA\Property(property="password", type="string", format="password")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/AuthUserResponse")
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * @param  LoginAuthRequest  $request
     * @return array
     *
     * @throws ValidationException
     */
    public function login(LoginAuthRequest $request): array
    {
        $user = User::where('email', $request->validated('email'))->first();

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->banned_at) {
            throw ValidationException::withMessages([
                'message' => ['You have been banned'],
            ]);
        }

        $expiredAt = CarbonImmutable::now()->add(
            $request->validated('remember_me', false)
                ? config('auth.api_token_prolonged_expires_in')
                : config('auth.api_token_expires_in')
        );

        $this->userStorage->update($user, ['last_login' => CarbonImmutable::now()]);

        return [
            'user' => new AuthUserResource($user),
            'token' => $user->createToken('api', expiresAt: $expiredAt)->plainTextToken,
            'expired_at' => $expiredAt->toDateTimeString(),
        ];
    }

    /**
     * @OA\Post(
     *     path="/admin/auth/logout",
     *     tags={"Auth"},
     *     security={ {"sanctum": {} }},
     *     summary="User Logout",
     *     @OA\Response(
     *          response=200,
     *          description="success"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     )
     * )
     */
    public function logout(): void
    {
        Auth::user()->tokens()->delete();
    }

    /**
     * @OA\Get(
     *     path="/admin/auth/user",
     *     tags={"Auth"},
     *     security={ {"sanctum": {} }},
     *     summary="Authorized user data",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/AuthUserResource")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     )
     * )
     *
     * @return AuthUserResource
     */
    public function user(): AuthUserResource
    {
        return new AuthUserResource(auth()->user());
    }
}
