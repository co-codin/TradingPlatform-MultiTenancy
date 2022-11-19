<?php

namespace Modules\Brand\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Brand\Dto\BrandDto;
use Modules\Brand\Http\Requests\BrandCreateRequest;
use Modules\Brand\Http\Requests\BrandUpdateRequest;
use Modules\Brand\Http\Resources\BrandResource;
use Modules\Brand\Models\Brand;
use Modules\Brand\Repositories\BrandRepository;
use Modules\Brand\Services\BrandStorage;

class BrandController extends Controller
{
    public function __construct(
        protected BrandRepository $brandRepository,
        protected BrandStorage $brandStorage,

    ) {
    }

    public function all()
    {
//        $this->authorize('viewAny');

        $brands = $this->brandRepository->all();

        return BrandResource::collection($brands);
    }

    public function index()
    {
//        $this->authorize('viewAny', Brand::class);

        $brands = $this->brandRepository->jsonPaginate();

        return BrandResource::collection($brands);
    }

    public function show(int $brand)
    {
        $brand = $this->brandRepository->find($brand);

//        $this->authorize('view', $brand);

        return new BrandResource($brand);
    }

    public function store(BrandCreateRequest $request)
    {
//        $this->authorize('create',  Brand::class);

        $brandDto = BrandDto::fromFormRequest($request);

        $brand = $this->brandStorage->store($brandDto);

        return new BrandResource($brand);
    }

    public function update(int $brand, BrandUpdateRequest $request)
    {
        $brand = $this->brandRepository->find($brand);

//        $this->authorize('update', $brand);

        $brand = $this->brandStorage->update(
            $brand, BrandDto::fromFormRequest($request)
        );

        return new BrandResource($brand);
    }

    public function destroy(int $brand)
    {
        $brand = $this->brandRepository->find($brand);

//        $this->authorize('delete', $brand);

        $this->brandStorage->delete($brand);

        return response()->noContent();
    }
}
