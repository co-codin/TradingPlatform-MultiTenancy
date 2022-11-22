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
