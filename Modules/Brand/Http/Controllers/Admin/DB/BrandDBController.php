<?php
declare(strict_types=1);

namespace Modules\Brand\Http\Controllers\Admin\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Modules\Brand\Http\Requests\BrandDBCreateRequest;
use Modules\Brand\Services\BrandDBService;

class BrandDBController extends Controller
{
    public function __construct(
        protected BrandDBService $brandDBService
    ){}

    public function store(int $brand, BrandDBCreateRequest $request)
    {
        $request->validated();

        $this
            ->brandDBService
            ->setBrand()
            ->setTables($request->input('tables'))
            ->migrateDB();

        return response(status: Response::HTTP_ACCEPTED);

    }
}
