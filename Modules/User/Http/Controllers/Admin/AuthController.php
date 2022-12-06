<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     *     summary="Steteful login worker",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={
     *                      "login",
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
     *          response=204,
     *          description="No content. The session ID is returned in a cookie named `laravel_session`. You need to include this cookie in subsequent requests.",
     *          headers={
     *              @OA\Header(
     *                  header="Set-Cookie(example-1)",
     *                  description="Encrypted session ID cookie",
     *                  @OA\Schema(
     *                      type="string",
     *                      example="laravel_session=eyJpdiI6IjZKZm...%3D; Path=/; Domain=localhost; HttpOnly; Expires=Fri, 18 Nov 2022 13:25:26 GMT;"
     *                  )
     *              ),
     *              @OA\Header(
     *                  header="Set-Cookie(example-2)",
     *                  description="Encrypted recaller cookie. Set if `remember_me=true` was received",
     *                  @OA\Schema(
     *                      type="string",
     *                      example="remember_web_59ba...=eyJpdiI6IjZKZm...%3D; Path=/; Domain=localhost; HttpOnly; Expires=Fri, 18 Nov 2022 13:25:26 GMT;"
     *                  )
     *              )
     *          }
     *     ),
     *     @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * @param  LoginRequest  $request
     * @return Response
     *
     * @throws ValidationException
     * @throws \Exception
     * @noinspection NullPointerExceptionInspection
     */
    public function login(LoginRequest $request): Response
    {
        $login = $request->validated('login');
        $loginType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (
            ! Auth::guard('web')->attempt([
                $loginType => $login, 'password' => $request->validated('password'),
            ], $request->validated('remember_me', false))
        ) {
            throw ValidationException::withMessages([
                'credentials' => 'The provided credentials are incorrect.',
            ]);
        }

        $user = Auth::user();
        if ($user->banned_at) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'banned' => 'You have been banned',
            ]);
        }

        $request->session()->regenerate();
        $this->userStorage->update($user, ['last_login' => CarbonImmutable::now()]);

        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/admin/auth/logout",
     *     tags={"Auth"},
     *     security={ {"sanctum_frontend": {} }},
     *     summary="Steteful logout worker",
     *     @OA\Response(
     *          response=204,
     *          description="No content"
     *     ),
     *     @OA\Response(
     *          response=419,
     *          description="CSRF token mismatch"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     )
     * )
     *
     * @param  Request  $request
     * @return Response
     */
    public function logout(Request $request): Response
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/admin/auth/user",
     *     tags={"Auth"},
     *     security={ {"sanctum_frontend": {} }},
     *     summary="Authorized worker data",
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
