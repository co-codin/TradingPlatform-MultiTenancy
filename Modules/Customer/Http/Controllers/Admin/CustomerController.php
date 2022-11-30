<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Http\Requests\CustomerBanRequest;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\Customer\Services\CustomerBanService;
use Modules\Customer\Services\CustomerStorage;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class CustomerController extends Controller
{
    /**
     * @param  CustomerBanService  $customerBanService
     * @param  CustomerRepository  $repository
     * @param  CustomerStorage  $storage
     */
    public function __construct(
        protected CustomerBanService $customerBanService,
        protected CustomerRepository $repository,
        protected CustomerStorage $storage,
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
        $customers = $this->customerBanService
            ->setAuthUser($request->user())
            ->banCustomers($request->validated('customers', []));

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
        $customers = $this->customerBanService
            ->setAuthUser($request->user())
            ->unbanCustomers($request->validated('customers', []));

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
}
