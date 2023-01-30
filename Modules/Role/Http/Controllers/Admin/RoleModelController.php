<?php

declare(strict_types=1);

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Role\Http\Resources\RoleModelResource;
use Modules\Role\Repositories\ModelRepository;
use Modules\Role\Repositories\RoleRepository;
use OpenApi\Annotations as OA;

final class RoleModelController extends Controller
{
    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly ModelRepository $modelRepository
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/roles/{id}/models",
     *     tags={"Role"},
     *     security={ {"sanctum": {} }},
     *     summary="Get RoleModels",
     *     @OA\Parameter(
     *         description="Role ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/RoleModelCollection")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     )
     * )
     *
     * Index RoleModel.
     *
     * @param  int  $id
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(int $id): AnonymousResourceCollection
    {
        $role = $this->roleRepository->find($id);
        $this->authorize('view', $role);

        $collection = RoleModelResource::collection($this->modelRepository->jsonPaginate(defaultSize: 10));
        $collection->collection->map(function ($resource) use ($role) {
            $resource->role = $role;

            return $resource;
        });

        return $collection;
    }

    /**
     * @OA\Get(
     *     path="/admin/roles/{id}/models/{modelId}",
     *     tags={"Role"},
     *     security={ {"sanctum": {} }},
     *     summary="Get RoleModel data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Role ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Parameter(
     *         description="Model ID",
     *         in="path",
     *         name="modelId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/RoleModelResource")
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized Error"
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden Error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Not Found"
     *     )
     * )
     *
     * View RoleModel.
     *
     * @param  int  $id
     * @param  int  $modelId
     * @return RoleModelResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id, int $modelId): RoleModelResource
    {
        $role = $this->roleRepository->find($id);
        $this->authorize('view', $role);

        $resource = new RoleModelResource($this->modelRepository->find($modelId));
        $resource->role = $role;

        return $resource;
    }
}
