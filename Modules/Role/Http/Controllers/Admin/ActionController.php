<?php

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Action;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Role\Http\Resources\ActionResource;
use Modules\Role\Repositories\ActionRepository;

class ActionController extends Controller
{
    public function __construct(
        protected ActionRepository $actionRepository
    ) {
    }
    public function all(): AnonymousResourceCollection
    {
//        $this->authorize('viewAny', Action::class);

        return ActionResource::collection(
            $this->actionRepository->all()
        );
    }
}
