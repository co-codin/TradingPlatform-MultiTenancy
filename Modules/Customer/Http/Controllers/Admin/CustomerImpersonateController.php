<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Http\Resources\AuthCustomerResource;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\User\Http\Controllers\Admin\AuthController;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use OpenApi\Annotations as OA;

final class CustomerImpersonateController extends Controller
{
    public function __construct(
        protected CustomerRepository $repository,
        protected UserRepository $userRepository,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/admin/customers/{id}/impersonate/session",
     *     tags={"Customer"},
     *     security={ {"sanctum_frontend": {} }},
     *     summary="Steteful login impersonate customer",
     *     @OA\Parameter(
     *         description="Customer id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1"
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Success. The session ID is returned in a cookie named `laravel_session`. You need to include this cookie in subsequent requests.",
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
     *                      example="remember_web-customer_59ba...=eyJpdiI6IjZKZm...%3D; Path=/; Domain=localhost; HttpOnly; Expires=Fri, 18 Nov 2022 13:25:26 GMT;"
     *                  )
     *              )
     *          },
     *          @OA\JsonContent(
     *              type="object",
     *              required={
     *                  "impersonator",
     *                  "target_customer"
     *              },
     *              @OA\Property(property="impersonator", type="object", ref="#/components/schemas/AuthUser"),
     *              @OA\Property(property="target_customer", type="object", ref="#/components/schemas/AuthCustomer")
     *          )
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
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     *
     * @throws AuthorizationException
     * @noinspection NullPointerExceptionInspection
     */
    public function session(Request $request, int $id): Response
    {
        $impersonator = auth(AuthController::GUARD)->user();
        $targetCustomer = $this->repository->find($id);

        $this->authorize('impersonate', $targetCustomer);

        $impersonatorRememberMe = $request->hasCookie(Auth::guard(AuthController::GUARD)->getRecallerName());

        Auth::guard(Customer::DEFAULT_AUTH_GUARD)->login($targetCustomer);

        $request->session()->regenerate();

        session([
            'impersonator_id' => $impersonator->id,
            'impersonator_remember_me' => $impersonatorRememberMe,
        ]);

        return response([
            'impersonator' => new AuthUserResource($impersonator),
            'target_customer' => new AuthCustomerResource($targetCustomer),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/admin/customers/impersonate/session/logout",
     *     tags={"Customer"},
     *     security={ {"sanctum_frontend": {} }},
     *     summary="Steteful logout impersonate customer",
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
     *          },
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
    public function sessionLogout(Request $request): Response
    {
        abort_if(
            session()->missing('impersonator_id'),
            Response::HTTP_UNAUTHORIZED,
            'Impersonator session missing'
        );

        $impersonator = $this->userRepository->find(session('impersonator_id'));
        $rememberMe = session('impersonator_remember_me', false);

        auth(Customer::DEFAULT_AUTH_GUARD)->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Auth::guard(AuthController::GUARD)->login($impersonator, $rememberMe);
        $request->session()->regenerate();

        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/admin/customers/{id}/impersonate/token",
     *     tags={"Customer"},
     *     security={ {"sanctum": {} }},
     *     summary="Stateless api login impersonate customer",
     *     @OA\Parameter(
     *         description="Customer id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         example="1"
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              required={
     *                  "impersonator",
     *                  "impersonator_token",
     *                  "target_customer",
     *                  "target_token",
     *                  "target_token_expired_at"
     *              },
     *              @OA\Property(property="impersonator", type="object", ref="#/components/schemas/AuthUser"),
     *              @OA\Property(property="target_customer", type="object", ref="#/components/schemas/AuthCustomer"),
     *              @OA\Property(property="impersonator_token", type="string"),
     *              @OA\Property(property="target_token", type="string"),
     *              @OA\Property(property="target_token_expired_at", type="string", format="date-time", example="2022-12-17 08:44:09")
     *          )
     *     ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation Error",
     *          @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     )
     * )
     *
     * @throws AuthorizationException
     */
    public function token(Request $request, int $id): Response
    {
        $impersonator = auth(User::DEFAULT_AUTH_GUARD)->user();
        $targetCustomer = $this->repository->find($id);

        $this->authorize('impersonate', $targetCustomer);

        $expiredAt = CarbonImmutable::now()->add(config('auth.api_token_expires_in'));
        $targetToken = $targetCustomer->createToken('api', expiresAt: $expiredAt);

        return response([
            'impersonator' => new AuthUserResource($impersonator),
            'impersonator_token' => $request->bearerToken(),
            'target_customer' => new AuthCustomerResource($targetCustomer),
            'target_token' => $targetToken->plainTextToken,
            'target_token_expired_at' => $expiredAt->toDateTimeString(),
        ]);
    }
}
