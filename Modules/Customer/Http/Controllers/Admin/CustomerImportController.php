<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Storage\StorageService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Excel;
use Modules\Customer\Jobs\CustomerImportJob;
use Modules\Customer\Models\Customer;
use Modules\Customer\Services\CustomerStorage;
use Spatie\Multitenancy\Models\Tenant;

final class CustomerImportController extends Controller
{
    /**
     * @param  CustomerStorage  $customerStorage
     */
    public function __construct(
        protected CustomerStorage $customerStorage,
        protected StorageService $storageService,
    ) {
    }

    /**
     * @OA\Post  (
     *     path="/admin/customers/import/excel",
     *     tags={"Customer"},
     *     security={ {"sanctum": {} }},
     *     summary="Import customers excel data",
     *     @OA\Property(property="file", type="string", format="binary"),
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
     * Import excel.
     *
     * @param  Request  $request
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function excel(Request $request): Response
    {
        $this->authorize('import', Customer::class);

        $filePath = $this->storageService->saveTmp('import', $request->file('file'));

        CustomerImportJob::dispatch(
            Tenant::current(),
            $filePath,
            null,
            Excel::XLSX,
        );

        return response('', 200);
    }

    /**
     * @OA\Post  (
     *     path="/admin/customers/import/csv",
     *     tags={"Customer"},
     *     security={ {"sanctum": {} }},
     *     summary="Import customers csv data",
     *     @OA\Property(property="file", type="string", format="binary"),
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
     * Import csv.
     *
     * @param  Request  $request
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function csv(Request $request): Response
    {
        $this->authorize('import', Customer::class);

        $filePath = $this->storageService->saveTmp('import', $request->file('file'));

        CustomerImportJob::dispatch(
            Tenant::current(),
            $filePath,
            null,
            Excel::CSV,
        );

        return response('', 200);
    }
}
