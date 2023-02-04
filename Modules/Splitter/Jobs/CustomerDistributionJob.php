<?php

declare(strict_types=1);

namespace Modules\Splitter\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Brand\Models\Brand;
use Modules\Customer\Models\Customer;
use Modules\Splitter\Services\CustomerDistribution;

final class CustomerDistributionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public int $customerId,
        public Brand $brand,
    ) {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->brand->makeCurrent();

        (new CustomerDistribution)->run(
            Customer::find($this->customerId),
            $this->brand
        );
    }
}
