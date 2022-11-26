<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Controllers\Admin\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Modules\Brand\Http\Requests\BrandDBImportRequest;
use Modules\Brand\Repositories\BrandRepository;
use Modules\Brand\Services\BrandDBService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

final class BrandDBController extends Controller
{
    /**
     * @param BrandDBService $brandDBService
     * @param BrandRepository $brandRepository
     */
    public function __construct(
        protected BrandDBService $brandDBService,
        protected BrandRepository $brandRepository,
    ){}

    /**
     * @OA\Post(
     *      path="/admin/brands/{id}/db/import",
     *      operationId="brands.db.import",
     *      security={ {"sanctum": {} }},
     *      tags={"Brand"},
     *      summary="Import db brand",
     *      description="Import tables into brand db.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                      "modules",
     *                 },
     *                 @OA\Property(property="modules", type="array", @OA\Items(type="string")),
     *             ),
     *         ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * Import db.
     *
     * @param BrandDBImportRequest $request
     * @param int $brand
     * @return Response
     */
    public function import(BrandDBImportRequest $request, int $brand): Response
    {
        $this->brandDBService
            ->setBrand($this->brandRepository->find($brand))
            ->setModules($request->input('modules'))
            ->migrateDB()
            ->seedData();

        return response(status: ResponseAlias::HTTP_ACCEPTED);
    }
}
