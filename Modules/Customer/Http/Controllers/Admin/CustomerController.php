<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Http\Requests\CustomerCreateRequest;
use Modules\Customer\Http\Requests\CustomerBanRequest;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\Customer\Services\CustomerBanService;
use Modules\Customer\Services\CustomerStorage;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Modules\User\Models\User;
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
     *      operationId="customers.store",
     *      security={ {"sanctum": {} }},
     *      tags={"Customers"},
     *      summary="Store customer",
     *      description="Returns customers data.",
     *      @OA\Parameter(
     *          description="Name",
     *          in="query",
     *          name="name",
     *          required=true,
     *          example="Any name"
     *      ),
     *      @OA\Parameter(
     *          description="Title",
     *          in="query",
     *          name="title",
     *          required=true,
     *          example="Any title"
     *      ),
     *       @OA\Parameter(
     *          description="Color",
     *          in="query",
     *          name="color",
     *          required=true,
     *          example="#e1e1e1"
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
     * @param CustomerCreateRequest $request
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(CustomerCreateRequest $request): JsonResource
    {
        // $this->authorize('create', SaleStatus::class);

        return new CustomerResource(
            $this->storage->store(CustomerDto::fromFormRequest($request)),
        );
    }
}
