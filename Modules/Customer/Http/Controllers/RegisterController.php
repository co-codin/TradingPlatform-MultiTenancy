<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Http\Requests\CustomerRegisterRequest;
use Modules\Customer\Services\CustomerStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class RegisterController extends Controller
{
    public function __construct(
        protected CustomerStorage $customerStorage,
    ) {
    }

    /**
     * @OA\Post(
     *      path="/customer/auth/register",
     *      tags={"Customer"},
     *      summary="Register customer",
     *      description="Registers a customer",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "first_name",
     *                     "last_name",
     *                     "gender",
     *                     "email",
     *                     "password",
     *                     "password_confirmation",
     *                     "phone",
     *                     "country_id",
     *                 },
     *                 @OA\Property(property="first_name", type="string", description="First name"),
     *                 @OA\Property(property="last_name", type="string", description="Last name"),
     *                 @OA\Property(property="gender", type="integer", description="1-Male, 2-Female, 3-Other", example="1", enum={1,2,3}),
     *                 @OA\Property(property="email", type="string", format="email", description="Email"),
     *                 @OA\Property(property="password", format="password", type="string", description="Password of customer", minLength=6),
     *                 @OA\Property(property="password_confirmation", format="password", type="string", description="Password confirmation", minLength=6),
     *                 @OA\Property(property="phone", type="string", format="phone", description="Phone", pattern="^\+(?:\d){6,14}\d$"),
     *                 @OA\Property(property="country_id", type="integer", description="Country id"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *           response=204,
     *           description="No content. The session ID is returned in a cookie named `laravel_session`. You need to include this cookie in subsequent requests.",
     *           headers={
     *               @OA\Header(
     *                   header="Set-Cookie(example-1)",
     *                   description="Encrypted session ID cookie",
     *                   @OA\Schema(
     *                       type="string",
     *                       example="laravel_session=eyJpdiI6IjZKZm...%3D; Path=/; Domain=localhost; HttpOnly; Expires=Fri, 18 Nov 2022 13:25:26 GMT;"
     *                   )
     *               ),
     *               @OA\Header(
     *                   header="Set-Cookie(example-2)",
     *                   description="Encrypted recaller cookie. Set if `remember_me=true` was received",
     *                   @OA\Schema(
     *                       type="string",
     *                       example="remember_web_59ba...=eyJpdiI6IjZKZm...%3D; Path=/; Domain=localhost; HttpOnly; Expires=Fri, 18 Nov 2022 13:25:26 GMT;"
     *                   )
     *               )
     *           }
     *      ),
     *      @OA\Response(
     *           response=419,
     *           description="CSRF token mismatch"
     *      ),
     *      @OA\Response(
     *           response=422,
     *           description="Validation Error",
     *           @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *      )
     * )
     *
     * Register customer.
     *
     * @param  CustomerRegisterRequest  $request
     * @return Response
     *
     * @throws UnknownProperties
     */
    public function register(CustomerRegisterRequest $request): Response
    {
        $customer = $this->customerStorage->store(CustomerDto::fromFormRequest($request));

        auth('web-customer')->login($customer);

        $request->session()->regenerate();

        return response()->noContent();
    }
}
