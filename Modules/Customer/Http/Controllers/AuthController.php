<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Customer\Http\Requests\LoginRequest;
use Modules\Customer\Http\Resources\AuthCustomerResource;
use Modules\Customer\Services\CustomerStorage;
use OpenApi\Annotations as OA;

final class AuthController extends Controller
{
    public const GUARD = 'web-customer';

    public function __construct(
        protected CustomerStorage $customerStorage,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/customer/auth/login",
     *     tags={"Customer"},
     *     summary="Steteful login customer",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  required={
     *                      "email",
     *                      "password"
     *                  },
     *                  type="object",
     *                  @OA\Property(property="email", type="string", format="email", description="Email"),
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
     * @noinspection NullPointerExceptionInspection
     */
    public function login(LoginRequest $request): Response
    {
        if (
            !Auth::guard(self::GUARD)->attempt([
                'email' => $request->validated('email'), 'password' => $request->validated('password'),
            ], $request->validated('remember_me', false))
        ) {
            throw ValidationException::withMessages([
                'credentials' => 'The provided credentials are incorrect.',
            ]);
        }

        $customer = Auth::guard(self::GUARD)->user();
        if ($customer->banned_at) {
            Auth::guard(self::GUARD)->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'banned' => 'You have been banned',
            ]);
        }

        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/customer/auth/logout",
     *     tags={"Customer"},
     *     security={ {"sanctum_frontend": {} }},
     *     summary="Steteful logout customer",
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
        Auth::guard(self::GUARD)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/customer/auth/me",
     *     tags={"Customer"},
     *     security={ {"sanctum_frontend": {} }},
     *     summary="Authorized customer data",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/AuthCustomerResource")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     )
     * )
     *
     * @return AuthCustomerResource
     */
    public function me(): AuthCustomerResource
    {
        return new AuthCustomerResource(auth()->user());
    }
}
