<?php

namespace Modules\Brand\Services;

use Illuminate\Support\Facades\DB;
use Modules\Brand\Dto\BrandDto;
use Modules\Brand\Jobs\CreateSchemaJob;
use Modules\Brand\Models\Brand;

class BrandStorage
{
    public function store(BrandDto $brandDto)
    {
        $brand = Brand::query()->create($brandDto->toArray());

        auth('sanctum')->user()->brands()->attach($brand->id);

//        dispatch(new CreateSchemaJob($brand->slug));

        return $brand;
    }

    public function update(Brand $brand, BrandDto $brandDto)
    {
        $attributes = $brandDto->toArray();

        if (!$brand->update($attributes)) {
            throw new \LogicException('can not update brand.');
        }

        return $brand;
    }

    public function delete(Brand $brand)
    {
        if (!$brand->delete()) {
            throw new \LogicException('can not delete brand');
        }
    }
}
