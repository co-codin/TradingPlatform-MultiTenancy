<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Http\Resources\AffiliateCustomerResource;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\CustomerRepository;

final class FTDCustomerController extends Controller
{
    /**
     * @param  CustomerRepository  $customerRepository
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
    ) {
    }

    /**
     * Display ftd customer list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAnyByAffiliate', Customer::class);

        return AffiliateCustomerResource::collection(
            $this->customerRepository
                ->where('affiliate_user_id', auth()->id())
                ->where('is_ftd', true)
                ->jsonPaginate()
        );
    }
}
