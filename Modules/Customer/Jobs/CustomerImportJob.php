<?php

declare(strict_types=1);

namespace Modules\Customer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Modules\Brand\Models\Brand;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Http\Requests\CustomerCreateRequest;
use Modules\Customer\Imports\CustomerImport;
use Modules\Customer\Services\CustomerStorage;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class CustomerImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        public Brand $brand,
        public ?string $filePath,
        public ?string $disk = null,
        public ?string $readerType = null
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @param CustomerStorage $customerStorage
     * @return void
     * @throws UnknownProperties
     */
    public function handle(CustomerStorage $customerStorage): void
    {
        $data = ExcelFacade::toArray(
            new CustomerImport,
            $this->filePath,
            $this->disk,
            $this->readerType,
        );

        $customerCreateRequest = new CustomerCreateRequest;

        $this->brand->makeCurrent();

        foreach ($data as $sheets) {
            foreach ($sheets as $sheet) {
                $customerStorage->updateOrStore(
                    new CustomerDto(
                        $customerCreateRequest->merge($sheet)
                            ->validate($customerCreateRequest->rules())
                    )
                );
            }
        }
    }
}
