<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\Customer\Services\CustomerStorage;

final class CustomerController extends Controller
{
    /**
     * @param CustomerRepository $repository
     * @param CustomerStorage $storage
     */
    public function __construct(
        protected CustomerRepository $repository,
        protected CustomerStorage $storage,
    ) {
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
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Customer::class);

        return CustomerResource::collection($this->repository->jsonPaginate());
    }
}
