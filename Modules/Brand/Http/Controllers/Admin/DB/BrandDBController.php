<?php
declare(strict_types=1);

namespace Modules\Brand\Http\Controllers\Admin\DB;

use App\Http\Controllers\Controller;
use Modules\Brand\Http\Requests\BrandDBCreateRequest;
use Modules\Brand\Repositories\BrandRepository;
use Modules\Brand\Services\BrandDBService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class BrandDBController extends Controller
{
    public function __construct(
        protected BrandDBService $brandDBService,
        protected BrandRepository $brandRepository,
    ){}

    public function import(BrandDBCreateRequest $request, int $brand)
    {
        $this->brandDBService
            ->setBrand($this->brandRepository->find($brand))
            ->setModules($request->input('modules'))
            ->migrateDB()
            ->migrateData()
        ;

        return response(status: ResponseAlias::HTTP_ACCEPTED);
    }
}
