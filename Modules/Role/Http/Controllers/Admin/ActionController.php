<?php

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Action;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Role\Http\Resources\ActionResource;
use Modules\Role\Repositories\ActionRepository;

class ActionController extends Controller
{
    public function __construct(
        protected ActionRepository $actionRepository
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/actions/all",
     *      security={ {"sanctum": {} }},
     *      tags={"Role"},
     *      summary="Get actions list all",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ActionCollection")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * Display roles list all.
     *
     * @throws AuthorizationException
     */
    public function all(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Action::class);

        return ActionResource::collection(
            $this->actionRepository->all()
        );
    }
}
