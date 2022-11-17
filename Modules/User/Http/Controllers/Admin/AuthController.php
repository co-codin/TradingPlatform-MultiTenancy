<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Http\Resources\AuthUserResource;
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
     *          description="success"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * @param  LoginRequest  $request
     *
     * @throws ValidationException
     * @throws \Exception
     */
    public function login(LoginRequest $request): void
    {
        if (Auth::attempt($request->validated())) {
            if (Auth::user()->banned_at) {
                throw ValidationException::withMessages([
                    'message' => ['You have been banned'],
                ]);
            }
            $request->session()->regenerate();

            $this->userStorage->update(Auth::user(), ['last_login' => CarbonImmutable::now()]);

            return;
        }

        throw ValidationException::withMessages([
            'message' => ['The provided credentials are incorrect.'],
        ]);
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
     *
     * @param  Request  $request
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
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
