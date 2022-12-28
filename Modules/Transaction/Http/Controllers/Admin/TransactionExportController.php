<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Modules\Transaction\Exports\TransactionExport;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Repositories\TransactionRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class TransactionExportController extends Controller
{
    /**
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        protected TransactionRepository $transactionRepository,
    ) {
    }

    /**
     * @OA\Post  (
     *     path="/admin/transactions/export/excel",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Export transactions excel data",
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
     *
     * @throws AuthorizationException
     */
    public function excel(): BinaryFileResponse
    {
        $this->authorize('export', Transaction::class);

        $export = new TransactionExport();
        $export->setCollection($this->transactionRepository->get());

        return ExcelFacade::download(
            $export,
            TransactionExport::EXPORT_FILE_NAME . '.xlsx',
            Excel::XLSX
        );
    }

    /**
     * @OA\Post  (
     *     path="/admin/transactions/export/csv",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Export transactions csv data",
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
     *
     * @throws AuthorizationException
     */
    public function csv(): BinaryFileResponse
    {
        $this->authorize('export', Transaction::class);

        $export = new TransactionExport();
        $export->setCollection($this->transactionRepository->get());

        return ExcelFacade::download(
            $export,
            TransactionExport::EXPORT_FILE_NAME . '.xlsx',
            Excel::CSV
        );
    }
}
