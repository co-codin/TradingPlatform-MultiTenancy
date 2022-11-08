<?php

namespace Modules\Brand\Http\Controllers\Admin\DB;

use App\Http\Controllers\Controller;
use Modules\Brand\Http\Requests\BrandDBCreateRequest;
use Modules\Brand\Services\BrandDBService;

class BrandDBController extends Controller
{
    public function __construct(
        protected BrandDBService $brandDBService
    ){}

    public function store(BrandDBCreateRequest $request)
    {

    }
}
