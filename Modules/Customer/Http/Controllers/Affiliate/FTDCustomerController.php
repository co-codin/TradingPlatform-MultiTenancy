<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Http\Resources\AffiliateCustomerResource;
use Illuminate\Http\Request;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\Token\Repositories\TokenRepository;

final class FTDCustomerController extends Controller
{
    /**
     * @param  CustomerRepository  $customerRepository
     * @param  TokenRepository  $tokenRepository
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected TokenRepository $tokenRepository
    ) {
    }

    /**
     * @OA\Get(
     *      path="/affiliate/ftd-customers",
     *      security={ {"sanctum": {} }},
     *      tags={"Customer"},
     *      summary="Get affiliate customers with ftd list",
     *      description="Returns affiliate customers with ftd list data.",
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
     * Display ftd customer list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResource
    {
        $token = $this->tokenRepository->whereToken($request->header('AffiliateToken'))->firstOrFail();

        return AffiliateCustomerResource::collection(
            $this->customerRepository
                ->where('affiliate_user_id', $token->user_id)
                ->where('is_ftd', true)
                ->jsonPaginate()
        );
    }
}
