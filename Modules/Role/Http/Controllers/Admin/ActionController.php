<?php

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ActionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Column::class);

        return ColumnResource::collection(
            $this->columnRepository->jsonPaginate()
        );
    }
}
