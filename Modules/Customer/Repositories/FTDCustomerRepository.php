<?php

namespace Modules\Customer\Repositories;

use App\Repositories\BaseRepository;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\Criteria\CustomerRequestCriteria;

class FTDCustomerRepository extends BaseRepository
{
    /**
     * {@inheritDoc}
     */
    public function model(): string
    {
        return Customer::class;
    }

    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $this->pushCriteria(CustomerRequestCriteria::class);
    }
}
