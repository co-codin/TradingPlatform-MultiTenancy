<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Modules\Customer\Dto\CustomerDto;
use Modules\Customer\Http\Requests\CustomerCreateRequest;
use Modules\Customer\Imports\CustomerImport;
use Modules\Customer\Models\Customer;
use Modules\Customer\Services\CustomerStorage;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class CustomerImportController extends Controller
{
    /**
     * @param  CustomerStorage  $customerStorage
     */
    public function __construct(
        protected CustomerStorage $customerStorage,
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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function excel(Request $request): Response
    {
        $this->authorize('import', Customer::class);

        $data = ExcelFacade::toArray(
            new CustomerImport,
            $request->file('file'),
            null,
            Excel::XLSX,
        );

        $customerCreateRequest = new CustomerCreateRequest;
        $customerCreateRequest->merge($data);

        $validatedData = $this->validate($customerCreateRequest, $customerCreateRequest->rules());

        foreach ($validatedData as $value) {
            $this->customerStorage->updateOrStore(new CustomerDto($value));
        }

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
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function csv(Request $request): Response
    {
        $this->authorize('import', Customer::class);

        $data = ExcelFacade::toArray(
            new CustomerImport,
            $request->file('file'),
            null,
            Excel::CSV,
        );

        $customerCreateRequest = new CustomerCreateRequest;
        $customerCreateRequest->merge($data);

        $validatedData = $this->validate($customerCreateRequest, $customerCreateRequest->rules());

        foreach ($validatedData as $value) {
            $this->customerStorage->updateOrStore(new CustomerDto($value));
        }

        return response('', 200);
    }
}
