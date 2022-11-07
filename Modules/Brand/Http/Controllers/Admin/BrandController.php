<?php

namespace Modules\Brand\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $this->authorizeResource(Brand::class, 'brand');
    }

    public function all()
    {
        $this->authorize('viewAny');

        $brands = $this->brandRepository->all();

        return BrandResource::collection($brands);
    }

    public function index()
    {
        $brands = $this->brandRepository->jsonPaginate();

        return BrandResource::collection($brands);
    }

    public function show(Brand $brand)
    {
//        $brand = $this->brandRepository->find($brand);

        return new BrandResource($brand);
    }

    public function store(BrandCreateRequest $request)
    {
        $brandDto = BrandDto::fromFormRequest($request);

        $brand = $this->brandStorage->store($brandDto);

        return new BrandResource($brand);
    }

    public function update(int $brand, BrandUpdateRequest $request)
    {
        $brand = $this->brandRepository->find($brand);

        $brand = $this->brandStorage->update(
            $brand, BrandDto::fromFormRequest($request)
        );

        return new BrandResource($brand);
    }

    public function destroy(int $brand)
    {
        $brand = $this->brandRepository->find($brand);

        $this->brandStorage->delete($brand);

        return response()->noContent();
    }
}
