<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Admin;

use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Modules\Customer\Exports\CustomerExport;
use Modules\Customer\Repositories\CustomerRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class CustomerExportController
{
    /**
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        protected CustomerRepository $customerRepository,
    )
    {
    }

    /**
     * @OA\Post  (
     *     path="/admin/customers/export/excel",
     *     tags={"Customer"},
     *     security={ {"sanctum": {} }},
     *     summary="Export customers excel data",
     *     @OA\Response(
     *          response=200,
     *          description="success"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * Export excel.
     *
     * @return BinaryFileResponse
     */
    public function excel(): BinaryFileResponse
    {
        $export = new CustomerExport();
        $export->setCollection($this->customerRepository->get());

        return ExcelFacade::download(
            $export,
            CustomerExport::EXPORT_FILE_NAME . '.xlsx',
            Excel::XLSX
        );
    }

    /**
     * @OA\Post  (
     *     path="/admin/customers/export/csv",
     *     tags={"Customer"},
     *     security={ {"sanctum": {} }},
     *     summary="Export customers csv data",
     *     @OA\Response(
     *          response=200,
     *          description="success"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * Export csv.
     *
     * @return BinaryFileResponse
     */
    public function csv(): BinaryFileResponse
    {
        $export = new CustomerExport();
        $export->setCollection($this->customerRepository->get());

        return ExcelFacade::download(
            $export,
            CustomerExport::EXPORT_FILE_NAME . '.xlsx',
            Excel::CSV
        );
    }
}
