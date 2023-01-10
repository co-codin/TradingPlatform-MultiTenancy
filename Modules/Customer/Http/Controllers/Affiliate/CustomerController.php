<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\CustomerRepository;

final class CustomerController extends Controller
{
    /**
     * @param  CustomerRepository  $customerRepository
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/affiliate/customers",
     *      security={ {"sanctum": {} }},
     *      tags={"Customer"},
     *      summary="Get affiliate customers list",
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

        return CustomerResource::collection(
            $this->customerRepository
                ->where('affiliate_user_id', auth()->id())
                ->jsonPaginate()
        );
    }
}
