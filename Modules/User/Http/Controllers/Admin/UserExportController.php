<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Modules\User\Exports\WorkerExport;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class UserExportController extends Controller
{
    /**
     * @param  UserRepository  $userRepository
     */
    public function __construct(
        protected UserRepository $userRepository,
    ) {
    }

    /**
     * @OA\Post  (
     *     path="/admin/workers/export/excel",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Export workers excel data",
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
        $this->authorize('export', User::class);

        $export = new WorkerExport();
        $export->setCollection($this->userRepository->get());

        return ExcelFacade::download(
            $export,
            WorkerExport::EXPORT_FILE_NAME . '.xlsx',
            Excel::XLSX
        );
    }

    /**
     * @OA\Post  (
     *     path="/admin/workers/export/csv",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Export workers csv data",
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
        $this->authorize('export', User::class);

        $export = new WorkerExport();
        $export->setCollection($this->userRepository->get());

        return ExcelFacade::download(
            $export,
            WorkerExport::EXPORT_FILE_NAME . '.xlsx',
            Excel::CSV
        );
    }
}
