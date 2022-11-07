<?php

namespace Modules\Brand\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    }

    public function index()
    {

    }
}
