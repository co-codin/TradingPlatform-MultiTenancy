<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Requests\LoginTokenRequest;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Models\User;
use Modules\User\Services\UserStorage;
use OpenApi\Annotations as OA;

final class TokenController extends Controller
{
    public function __construct(
        protected UserStorage $userStorage,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/admin/token/login",
     *     tags={"Auth"},
     *     summary="Get a token by creds",
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
     *                  @OA\Property(property="email", type="string", format="email"),
     *                  @OA\Property(property="password", type="string", format="password"),
     *                  @OA\Property(property="remember_me", type="boolean")
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
     * @param  LoginTokenRequest  $request
     * @return array
     *
     * @throws ValidationException
     * @throws \Exception
     */
    public function login(LoginTokenRequest $request): array
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
     *     path="/admin/token/create",
     *     tags={"Auth"},
     *     security={ {"sanctum": {} }},
     *     summary="Create new token",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema (
     *                  required={
     *                      "token_name",
     *                  },
     *                  type="object",
     *                  @OA\Property(property="token_name", type="string")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Schema (
     *                  required={
     *                      "token",
     *                  },
     *                  type="object",
     *                  @OA\Property(property="token", type="string")
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * @param  Request  $request
     * @return array
     *
     * @throws ValidationException
     */
    public function create(Request $request): array
    {
        $request->validate([
            'token_name' => 'required',
        ]);

        return ['token' => $request->user()->createToken($request->token_name)->plainTextToken];
    }

    /**
     * @OA\Delete(
     *     path="/admin/token/delete",
     *     tags={"Auth"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete token",
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
    public function delete(Request $request): void
    {
        $request->validate([
            'token_name' => 'required',
        ]);
        Auth::user()->tokens()->where('name', $request->token_name)->delete();
    }
}
