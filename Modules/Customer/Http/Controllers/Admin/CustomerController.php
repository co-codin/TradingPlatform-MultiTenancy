<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Http\Requests\CustomerBanRequest;
use Modules\Customer\Http\Requests\CustomerCreateRequest;
use Modules\Customer\Http\Requests\CustomerUpdateRequest;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\Customer\Services\CustomerBanService;
use Modules\Customer\Services\CustomerStorage;
use Modules\User\Models\User;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class CustomerController extends Controller
{
    /**
     * @param  CustomerBanService  $customerBanService
     * @param  CustomerRepository  $customerRepository
     * @param  CustomerStorage  $customerStorage
     */
    public function __construct(
        protected CustomerBanService $customerBanService,
        protected CustomerRepository $customerRepository,
        protected CustomerStorage $customerStorage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/customers",
     *      security={ {"sanctum": {} }},
     *      tags={"Customer"},
     *      summary="Get customers list",
     *      description="Returns customers list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CustomerCollection")
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
        $this->authorize('viewAny', Customer::class);

        return CustomerResource::collection($this->customerRepository->jsonPaginate());
    }

    /**
     * @OA\Post(
     *      path="/admin/customers",
     *      security={ {"sanctum": {} }},
     *      tags={"Customer"},
     *      summary="Store customer",
     *      description="Returns customer data.",
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
     *                     "phone",
     *                     "country_id",
     *                     "language_id"
     *                 },
     *                 @OA\Property(property="first_name", type="string", description="First name"),
     *                 @OA\Property(property="last_name", type="string", description="Last name"),
     *                 @OA\Property(property="gender", type="integer", description="1-Male, 2-Female, 3-Other", example="1"),
     *                 @OA\Property(property="email", type="string", format="email", description="Email"),
     *                 @OA\Property(property="password", type="string", description="Password of customer"),
     *                 @OA\Property(property="phone", type="string", format="phone", description="Phone"),
     *                 @OA\Property(property="country_id", type="integer", description="Country id"),
     *                 @OA\Property(property="language_id", type="integer", description="Language id", nullable="true"),
     *                 @OA\Property(property="phone2", type="string", format="phone", description="Second phone", nullable="true"),
     *                 @OA\Property(property="city", type="string", description="City", nullable="true"),
     *                 @OA\Property(property="address", type="string", description="Address", nullable="true"),
     *                 @OA\Property(property="postal_code", type="integer", description="Post code"),
     *                 @OA\Property(property="desk_id", type="integer", description="Desk id", nullable="true"),
     *                 @OA\Property(property="offer_name", type="string", description="Offer name", nullable="true"),
     *                 @OA\Property(property="offer_url", type="string", description="Offer url", nullable="true"),
     *                 @OA\Property(property="comment_about_customer", type="string", description="Comment about customer", nullable="true"),
     *                 @OA\Property(property="source", type="string", description="Source", nullable="true"),
     *                 @OA\Property(property="click_id", type="string", description="Click id", nullable="true"),
     *                 @OA\Property(property="free_param_1", type="string", description="Free param 1", nullable="true"),
     *                 @OA\Property(property="free_param_2", type="string", description="Free param 2", nullable="true"),
     *                 @OA\Property(property="free_param_3", type="string", description="Free param 3", nullable="true"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CustomerResource")
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
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(CustomerCreateRequest $request): JsonResource
    {
        $this->authorize('create', Customer::class);

        return new CustomerResource(
            $this->customerStorage->store(CustomerDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/customers/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Customer"},
     *      summary="Get customer",
     *      description="Returns customer data.",
     *      @OA\Parameter(
     *         description="Customer ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CustomerResource")
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
     * Show the customer.
     *
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $customer = $this->customerRepository->find($id);

        $this->authorize('view', $customer);

        return new CustomerResource($customer);
    }

    /**
     * @OA\Put(
     *     path="/admin/customers/{id}",
     *     tags={"Customer"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a customer",
     *     @OA\Parameter(
     *         description="Customer ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="affiliate_user_id", type="integer", description="Affiliate worker ID"),
     *                 @OA\Property(property="conversion_user_id", type="integer", description="Conversion worker ID"),
     *                 @OA\Property(property="retention_user_id", type="integer", description="Retention user ID"),
     *                 @OA\Property(property="compliance_user_id", type="integer", description="Compliance worker ID"),
     *                 @OA\Property(property="support_user_id", type="integer", description="Support worker ID"),
     *                 @OA\Property(property="conversion_manager_user_id", type="integer", description="Conversion manager worker ID"),
     *                 @OA\Property(property="retention_manager_user_id", type="integer", description="Retention manager worker ID"),
     *                 @OA\Property(property="first_conversion_user_id", type="integer", description="First conversion worker ID"),
     *                 @OA\Property(property="first_retention_user_id", type="integer", description="First retention worker ID"),
     *                 @OA\Property(property="conversion_sale_status_id", type="integer", description="Conversion sale status ID"),
     *                 @OA\Property(property="retention_sale_status_id", type="integer", description="Retention sale status ID"),
     *                 @OA\Property(property="permissions", type="array",
     *                    @OA\Items(required={"id"},
     *                        @OA\Property(property="id", type="integer", description="Permission ID"),
     *                        @OA\Property(property="status", type="string", description="Status of customer permission"),
     *                        @OA\Property(property="data", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="reason", type="string", description="Reason of change status"),
     *                          ),
     *                        ),
     *                    ),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * ),
     * @OA\Patch(
     *     path="/admin/customers/{id}",
     *     tags={"Customer"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a customer",
     *     @OA\Parameter(
     *         description="Customer ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="affiliate_user_id", type="integer", description="Affiliate worker ID"),
     *                 @OA\Property(property="conversion_user_id", type="integer", description="Conversion worker ID"),
     *                 @OA\Property(property="retention_user_id", type="integer", description="Retention user ID"),
     *                 @OA\Property(property="compliance_user_id", type="integer", description="Compliance worker ID"),
     *                 @OA\Property(property="support_user_id", type="integer", description="Support worker ID"),
     *                 @OA\Property(property="conversion_manager_user_id", type="integer", description="Conversion manager worker ID"),
     *                 @OA\Property(property="retention_manager_user_id", type="integer", description="Retention manager worker ID"),
     *                 @OA\Property(property="first_conversion_user_id", type="integer", description="First conversion worker ID"),
     *                 @OA\Property(property="first_retention_user_id", type="integer", description="First retention worker ID"),
     *                 @OA\Property(property="conversion_sale_status_id", type="integer", description="Conversion sale status ID"),
     *                 @OA\Property(property="retention_sale_status_id", type="integer", description="Retention sale status ID"),
     *                 @OA\Property(property="permissions", type="array",
     *                    @OA\Items(required={"id"},
     *                        @OA\Property(property="id", type="integer", description="Permission ID"),
     *                        @OA\Property(property="status", type="string", description="Status of customer permission"),
     *                        @OA\Property(property="data", type="array",
     *                          @OA\Items(
     *                              @OA\Property(property="reason", type="string", description="Reason of change status"),
     *                          ),
     *                        ),
     *                    ),
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerResource")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * @param  CustomerUpdateRequest  $request
     * @param  int  $customer
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(CustomerUpdateRequest $request, int $customer): JsonResource
    {
        $customer = $this->customerRepository->find($customer);

        $this->authorize('update', $customer);

        $this->customerStorage->update($customer, CustomerDto::fromFormRequest($request));

        return new CustomerResource($customer);
    }

    /**
     * @OA\Patch (
     *     path="/customers/ban",
     *     tags={"Customer"},
     *     security={ {"sanctum": {} }},
     *     summary="Ban customers",
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                required={"customers"},
     *                @OA\Property(property="customers", type="array",
     *                    @OA\Items(required={"id"},
     *                        @OA\Property(property="id", type="integer", description="Customer ID")
     *                    ),
     *                )
     *            ),
     *        ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/CustomerCollection")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * Ban customer.
     *
     * @param  CustomerBanRequest  $request
     * @return JsonResource
     *
     * @throws Exception
     */
    public function ban(CustomerBanRequest $request): JsonResource
    {
        $customers = collect();

        foreach ($request->validated('customers', []) as $customerData) {
            $customer = $this->customerRepository->find($customerData['id']);

            if ($request->user()?->can('ban', $customer)) {
                $customers->push(
                    $this->customerBanService->banCustomer($customer),
                );
            }
        }

        abort_if($customers->isEmpty(), ResponseAlias::HTTP_UNAUTHORIZED);

        return CustomerResource::collection($customers);
    }

    /**
     * @OA\Patch (
     *     path="/customers/unban",
     *     tags={"Customer"},
     *     security={ {"sanctum": {} }},
     *     summary="Unban customers",
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                required={"customers"},
     *                @OA\Property(property="customers", type="array",
     *                    @OA\Items(required={"id"},
     *                        @OA\Property(property="id", type="integer", description="Customer ID")
     *                    ),
     *                )
     *            ),
     *        ),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/CustomerCollection")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * Ban customer.
     *
     * @param  CustomerBanRequest  $request
     * @return JsonResource
     *
     * @throws Exception
     */
    public function unban(CustomerBanRequest $request): JsonResource
    {
        $customers = collect();

        foreach ($request->validated('customers', []) as $customerData) {
            $customer = $this->customerRepository->find($customerData['id']);

            if ($request->user()?->can('unban', $customer)) {
                $customers->push(
                    $this->customerBanService->unbanCustomer($customer),
                );
            }
        }

        abort_if($customers->isEmpty(), ResponseAlias::HTTP_UNAUTHORIZED);

        return CustomerResource::collection($customers);
    }
}
