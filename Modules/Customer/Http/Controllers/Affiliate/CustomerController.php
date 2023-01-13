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
