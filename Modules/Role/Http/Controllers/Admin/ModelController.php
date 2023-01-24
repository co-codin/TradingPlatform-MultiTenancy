<?php

declare(strict_types=1);

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Model;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Role\Http\Resources\ModelResource;
use Modules\Role\Repositories\ModelRepository;
use OpenApi\Annotations as OA;

final class ModelController extends Controller
{
    public function __construct(
        protected ModelRepository $modelRepository
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/models/all",
     *      security={ {"sanctum": {} }},
     *      tags={"Role"},
     *      summary="Get models list all",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ModelCollection")
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
     * Display models list all.
     *
     * @throws AuthorizationException
     */
    public function all(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Model::class);

        return ModelResource::collection(
            $this->modelRepository->allWithoutNamespaces()
        );
    }
}
