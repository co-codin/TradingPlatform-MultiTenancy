<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Models\User;
use Modules\User\Services\UserStorage;
use OpenApi\Annotations as OA;

final class TokenAuthController extends Controller
{
    public function __construct(
        protected UserStorage $userStorage,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/admin/token-auth/login",
     *     tags={"Auth"},
     *     summary="Stateless api login",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={
     *                      "email",
     *                      "password"
     *                  },
     *                  type="object",
     *                  @OA\Property(property="login", type="string", description="Username or Email"),
     *                  @OA\Property(property="password", type="string", format="password"),
     *                  @OA\Property(property="remember_me", type="boolean")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              required={
     *                  "token",
     *                  "expired_at"
     *              },
     *              @OA\Property(property="token", type="string"),
     *              @OA\Property(property="expired_at", type="string", format="date-time", example="2022-12-17 08:44:09")
     *          )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * @param  LoginRequest  $request
     * @return array
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function login(LoginRequest $request): array
    {
        $login = $request->validated('login');
        $loginType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($loginType, $login)->first();

        if (! $user || ! Hash::check($request->validated('password'), $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => 'The provided credentials are incorrect.',
            ]);
        }

        if ($user->banned_at) {
            throw ValidationException::withMessages([
                'banned' => 'You have been banned',
            ]);
        }

        $expiredAt = CarbonImmutable::now()->add($request->validated('remember_me',
            false) ? config('auth.api_token_prolonged_expires_in') : config('auth.api_token_expires_in'));

        $this->userStorage->update($user, ['last_login' => CarbonImmutable::now()]);

        return [
            'token' => $user->createToken('api', expiresAt: $expiredAt)->plainTextToken,
            'expired_at' => $expiredAt->toDateTimeString(),
        ];
    }

    /**
     * @OA\Post(
     *     path="/admin/token-auth/logout",
     *     tags={"Auth"},
     *     security={ {"sanctum": {} }},
     *     summary="Steteless api logout",
     *     @OA\Response(
     *          response=204,
     *          description="No content"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     )
     * )
     *
     * @return Response
     */
    public function logout(): Response
    {
        Auth::user()->tokens()->where('name', 'api')->delete();

        return response()->noContent();
    }
}
