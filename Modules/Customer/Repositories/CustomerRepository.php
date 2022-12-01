<?php

namespace Modules\Customer\Repositories;

use App\Repositories\BaseRepository;
use Modules\Customer\Models\Customer;
use Modules\Customer\Repositories\Criteria\CustomerRequestCriteria;
use Modules\Customer\Repositories\Validator\PermissionColumnValidator;

class CustomerRepository extends BaseRepository
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
        $this->pushPermissionColumnValidator(PermissionColumnValidator::class);
        $this->pushCriteria(CustomerRequestCriteria::class);
    }
}
