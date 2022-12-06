<?php

declare(strict_types=1);

namespace Modules\Brand\Services;

use App\Jobs\CreateTenantDatabase;
use Modules\Brand\Dto\BrandDto;
use Modules\Brand\Jobs\MigrateStructureJob;
use Modules\Brand\Models\Brand;

final class BrandStorage
{
    /**
     * Store brand.
     *
     * @param BrandDto $brandDto
     * @return Brand
     */
    final public function store(BrandDto $brandDto): Brand
    {
        $brand = Brand::query()->create($brandDto->toArray());

        CreateTenantDatabase::dispatch($brand->slug);
        MigrateStructureJob::dispatch($brand);

        return $brand;
    }

    /**
     * Update brand.
     *
     * @param Brand $brand
     * @param BrandDto $brandDto
     * @return Brand
     */
    final public function update(Brand $brand, BrandDto $brandDto): Brand
    {
        $attributes = $brandDto->toArray();

        if (! $brand->update($attributes)) {
            throw new \LogicException('can not update brand.');
        }

        return $brand;
    }

    /**
     * Destroy brand.
     *
     * @param Brand $brand
     * @return void
     */
    final public function delete(Brand $brand): void
    {
        if (! $brand->delete()) {
            throw new \LogicException('can not delete brand');
        }
    }
}
