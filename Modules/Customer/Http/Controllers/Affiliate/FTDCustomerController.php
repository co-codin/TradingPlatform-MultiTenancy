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
     * Display ftd customer list.
     *
     * @return JsonResource
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
