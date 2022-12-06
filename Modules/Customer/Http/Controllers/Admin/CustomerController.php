<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Http\Requests\CustomerBanRequest;
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

            if ($request->user()?->can('banCustomer', [User::class, $customer])) {
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

            if ($request->user()?->can('unbanCustomer', [User::class, $customer])) {
                $customers->push(
                    $this->customerBanService->unbanCustomer($customer),
                );
            }
        }

        abort_if($customers->isEmpty(), ResponseAlias::HTTP_UNAUTHORIZED);

        return CustomerResource::collection($customers);
    }

    /**
     * @OA\Get(
     *      path="/admin/customers",
     *      operationId="customers.index",
     *      security={ {"sanctum": {} }},
     *      tags={"Customers"},
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

        return CustomerResource::collection($this->repository->jsonPaginate());
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
     * @param CustomerUpdateRequest $request
     * @param int $customer
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(CustomerUpdateRequest $request, int $customer): JsonResource
    {
        $customer = $this->customerRepository->find($customer);

        $this->authorize('update', $customer);

        $this->customerStorage->update($customer, new CustomerDto($request->validated()));

        return new CustomerResource($customer);
    }
}
