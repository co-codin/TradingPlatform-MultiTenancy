<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Brand\Models\Brand;
use Modules\Customer\Dto\UrlAuthDto;
use Modules\Customer\Models\Customer;
use Modules\Customer\Services\CustomerStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Spatie\Multitenancy\Models\Tenant;

final class UrlAuthController extends Controller
{
    public function __construct(
        protected CustomerStorage $storage,
    ) {
    }

    /**
     * @OA\Post(
     *     path="/customer/url-auth/login",
     *     tags={"CustomerAuth"},
     *     summary="One time stateful customer login",
     *     @OA\Parameter(
     *          name="key",
     *          in="query",
     *          required=true,
     *          description="One time key",
     *          @OA\Schema(type="string"),
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
     * )
     *
     * @param  Request  $request
     * @return Response
     *
     * @throws ValidationException|UnknownProperties
     */
    public function login(Request $request): Response
    {
        $key = 'url-auth:'.$request->query('key');
        $customerInfo = Cache::get($key);
        abort_if(!$customerInfo, Response::HTTP_NOT_FOUND);

        $urlAuthDto = UrlAuthDto::create($customerInfo);
        Brand::find($urlAuthDto->brandId)->makeCurrent();
        if (
            !Auth::guard(Customer::DEFAULT_AUTH_GUARD)->loginUsingId($urlAuthDto->customerId)
        ) {
            throw ValidationException::withMessages([
                'credentials' => 'The provided credentials are incorrect.',
            ]);
        }

        /** @var Customer $customer */
        $customer = Auth::guard(Customer::DEFAULT_AUTH_GUARD)->user();
        if ($customer->banned_at) {
            Auth::guard(Customer::DEFAULT_AUTH_GUARD)->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            throw ValidationException::withMessages([
                'banned' => 'You have been banned',
            ]);
        }

        $request->session()->regenerate();
        $customer->update(['last_online' => $customer->freshTimestamp()]);
        Cache::forget($key);

        return response()->noContent();
    }

    public function create(Request $request): array
    {
        $key = Str::random();

        Cache::put("url-auth:{$key}", [
            'customerId' => $request->query('customer_id'),
            'brandId' => Tenant::current()->id,
        ], now()->addMinutes(30));

        return ['url' => url(route('customer.url-auth.login', compact('key'), false))];
    }
}
