<?php

namespace Modules\Brand\Repositories;

use App\Repositories\BaseRepository;
use Modules\Brand\Models\Brand;
use Modules\Brand\Repositories\Criteria\BrandRequestCriteria;

class BrandRepository extends BaseRepository
{
    public function model()
    {
        return Brand::class;
    }

    public function boot()
    {
        $this->pushCriteria(BrandRequestCriteria::class);
    }
}
