<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\Customer\Services\CustomerBanService;
use Modules\Customer\Services\CustomerStorage;

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
     *      path="/affiliate/customers",
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
        $this->authorize('viewAnyByAffiliate', Customer::class);

        return CustomerResource::collection($this->customerRepository->jsonPaginate());
    }

    /**
     * @OA\Get(
     *      path="/affiliate/customers/{id}",
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

        $this->authorize('viewByAffiliate', $customer);

        return new CustomerResource($customer);
    }
}
