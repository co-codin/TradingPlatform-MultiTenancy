<?php

declare(strict_types=1);

namespace Modules\Customer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Brand\Models\Brand;
use Modules\Customer\Services\CopyCustomerService;

final class CopyCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public ?int $customerId,
        public Brand $brand,
        public ?int $destinationBrandId,
    ) {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new CopyCustomerService)->run(
            $this->customerId,
            $this->brand,
            Brand::find($this->destinationBrandId)
        );
    }
}
