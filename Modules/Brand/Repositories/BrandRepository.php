<?php

namespace Modules\Brand\Repositories;

use App\Repositories\BaseRepository;
use Modules\Brand\Models\Brand;
use Modules\Brand\Repositories\Criteria\BrandRequestCriteria;

class BrandRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Brand::class;
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->pushColumnPermissionValidator(BrandColumnPermissionValidator::class);
        $this->pushCriteria(BrandRequestCriteria::class);
    }
}
