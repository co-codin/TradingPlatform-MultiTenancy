<?php

declare(strict_types=1);

namespace Modules\Transaction\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Customer\Models\Customer;
use Modules\Department\Enums\DepartmentEnum;
use Modules\Department\Models\Department;
use Spatie\Multitenancy\Jobs\TenantAware;

class ChangeCustomerDepartmentAfterDeposit implements ShouldQueue, TenantAware
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param  Customer  $customer
     */
    public function __construct(private readonly Customer $customer)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->customer->department()->associate(Department::firstWhere('name', DepartmentEnum::CONVERSION));
        $this->customer->save();
    }
}
