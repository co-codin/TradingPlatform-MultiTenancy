<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\Customer\Http\Requests\LoginRequest;
use Modules\Customer\Models\Customer;
use Modules\Customer\Services\CustomerStorage;
use OpenApi\Annotations as OA;

final class TokenAuthController extends Controller
{
    /**
     * @var string
     */
    public const GUARD = 'api-customer';

    public function __construct(
        protected CustomerStorage $storage,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/customer/token-auth/login",
     *     tags={"Customer"},
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
     *                  @OA\Property(property="email", type="string", format="email"),
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
        $email = $request->validated('email');
        $customer = Customer::where('email', $email)->first();

        if (! $customer || ! Hash::check($request->validated('password'), $customer->password)) {
            throw ValidationException::withMessages([
                'credentials' => 'The provided credentials are incorrect.',
            ]);
        }

        if ($customer->banned_at) {
            throw ValidationException::withMessages([
                'banned' => 'You have been banned',
            ]);
        }

        $expiredAt = CarbonImmutable::now()->add($request->validated('remember_me', false)
            ? config('auth.api_token_prolonged_expires_in') : config('auth.api_token_expires_in'));

        $customer->update(['last_online' => $customer->freshTimestamp()]);

        return [
            'token' => $customer->createToken('api', expiresAt: $expiredAt)->plainTextToken,
            'expired_at' => $expiredAt->toDateTimeString(),
        ];
    }

    /**
     * @OA\Post(
     *     path="/customer/token-auth/logout",
     *     tags={"Customer"},
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
        Auth::guard(self::GUARD)->user()->tokens()->where('name', 'api')->delete();

        return response()->noContent();
    }
}
