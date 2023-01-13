<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Modules\Currency\Repositories\CurrencyRepository;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Http\Requests\Affiliate\CustomerCreateRequest;
use Modules\Customer\Http\Resources\AffiliateCustomerResource;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\Customer\Services\CustomerStorage;
use Modules\Geo\Repositories\CountryRepository;
use Modules\Language\Repositories\LanguageRepository;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class CustomerController extends Controller
{
    /**
     * @param  CustomerRepository  $customerRepository
     * @param  CurrencyRepository  $currencyRepository
     * @param  CountryRepository  $countryRepository
     * @param  LanguageRepository  $languageRepository
     * @param  CustomerStorage  $customerStorage
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected CurrencyRepository $currencyRepository,
        protected CountryRepository $countryRepository,
        protected LanguageRepository $languageRepository,
        protected CustomerStorage $customerStorage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/affiliate/customers",
     *      security={ {"sanctum": {} }},
     *      tags={"Customer"},
     *      summary="Get affiliate customers list",
     *      description="Returns affiliate customers list data.",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/AffiliateCustomerCollection")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * Display customer list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        return AffiliateCustomerResource::collection(
            $this->customerRepository
                ->where('affiliate_user_id', auth()->id())
                ->jsonPaginate()
        );
    }

    /**
     * @OA\Post(
     *      path="/affiliate/customers",
     *      security={ {"sanctum": {} }},
     *      tags={"Customer"},
     *      summary="Store affiliate customer",
     *      description="Returns affiliate customer data.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "first_name",
     *                     "last_name",
     *                     "phone",
     *                     "email",
     *                     "country",
     *                     "language",
     *                     "currency",
     *                     "campaign_id",
     *                 },
     *                 @OA\Property(property="first_name", type="string", description="First name"),
     *                 @OA\Property(property="last_name", type="string", description="Last name"),
     *                 @OA\Property(property="phone", type="string", description="Phone"),
     *                 @OA\Property(property="country", type="string", description="ISO2, ISO3 or name of country"),
     *                 @OA\Property(property="language", type="string", description="ISO2 or full name of language"),
     *                 @OA\Property(property="currency", type="string", description="ISO3 of currency"),
     *                 @OA\Property(property="campaign_id", type="integer", description="Available IDs for affiliate"),
     *                 @OA\Property(property="brand_id", type="integer", description="Brand ID"),
     *                 @OA\Property(property="desk_id", type="integer", description="Desk ID"),
     *                 @OA\Property(property="offer_name", type="string", description="Offer name"),
     *                 @OA\Property(property="offer_url", type="string", description="Offer URL"),
     *                 @OA\Property(property="comment_about_customer", type="string", description="Comment about customer"),
     *                 @OA\Property(property="source", type="string", description="Source"),
     *                 @OA\Property(property="click_id", type="string", description="Click ID"),
     *                 @OA\Property(property="free_param_1", type="string", description="Free param 1"),
     *                 @OA\Property(property="free_param_2", type="string", description="Free param 2"),
     *                 @OA\Property(property="free_param_3", type="string", description="Free param 3"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/AffiliateCustomerResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * Store customer.
     *
     * @param  CustomerCreateRequest  $request
     * @return Response
     *
     * @throws UnknownProperties
     */
    public function store(CustomerCreateRequest $request): Response
    {
        $country = $this->countryRepository
            ->whereLowerCase('iso2', strtolower($request->post('country')))
            ->orWhereLowerCase('iso3', strtolower($request->post('country')))
            ->orWhereLowerCase('name', strtolower($request->post('country')))
            ->firstOrFail();

        $language = $this->languageRepository
            ->whereLowerCase('code', strtolower($request->post('language')))
            ->orWhereLowerCase('name', strtolower($request->post('language')))
            ->firstOrFail();

        $currency = $this->currencyRepository
            ->whereLowerCase('iso3', strtolower($request->post('currency')))
            ->firstOrFail();

        $data = array_merge($request->validated(), [
            'country_id' => $country->id,
            'language_id' => $language->id,
            'currency_id' => $currency->id,
            'password' => $password = Str::random(),
            'affiliate_user_id' => auth()->id(),
        ]);

        $this->customerStorage->store(new CustomerDto($data));

        return response([
            'data' => [
                'password' => $password,
                'link' => '',
            ],
        ], 201);
    }
}