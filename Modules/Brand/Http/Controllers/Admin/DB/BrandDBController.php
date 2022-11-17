<?php
declare(strict_types=1);

namespace Modules\Brand\Http\Controllers\Admin\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Modules\Brand\Http\Requests\BrandDBCreateRequest;
use Modules\Brand\Repositories\BrandRepository;
use Modules\Brand\Services\BrandDBService;

class BrandDBController extends Controller
{
    public function __construct(
        protected BrandDBService $brandDBService,
        protected BrandRepository $brandRepository,
    ){}

    /**
     *
     *
     * @param int $brand
     * @param BrandDBCreateRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function store(BrandDBCreateRequest $request, int $brandId)
    {
        $request->validated();

        $this->brandDBService
            ->setBrand($this->brandRepository->find($brandId))
            ->setModules($request->input('modules'))
            ->migrateDB();

        return response(status: Response::HTTP_ACCEPTED);

    }

    public function import(BrandDBCreateRequest $request, int $brand_id)
    {
        $this->store($request, $brand_id);
    }
}
