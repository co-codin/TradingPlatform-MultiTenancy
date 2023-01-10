<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Http\Resources\FTDCustomerResource;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\FTDCustomerRepository;

final class FTDCustomerController extends Controller
{
    /**
     * @param  FTDCustomerRepository  $customerRepository
     */
    public function __construct(
        protected FTDCustomerRepository $customerRepository,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/affiliate/ftd-customers",
     *      security={ {"sanctum": {} }},
     *      tags={"Customer"},
     *      summary="Get ftd customers list",
     *      description="Returns ftd customers list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/FTDCustomerCollection")
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
     * Display ftd customer list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAnyByAffiliate', Customer::class);

        return FTDCustomerResource::collection(
            $this->customerRepository
                ->where('affiliate_user_id', auth()->id())
                ->where('is_ftd', true)
                ->jsonPaginate()
        );
    }
}
