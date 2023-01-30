<?php

declare(strict_types=1);

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Action;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Role\Http\Requests\Permission\RoleModelUpdateRequest;
use Modules\Role\Http\Resources\RoleModelResource;
use Modules\Role\Models\Column;
use Modules\Role\Models\Permission;
use Modules\Role\Repositories\ModelRepository;
use Modules\Role\Repositories\RoleRepository;
use OpenApi\Annotations as OA;

final class RoleModelController extends Controller
{
    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly ModelRepository $modelRepository,
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

    /**
     * @OA\Put(
     *     path="/admin/roles/{id}/models/{modelId}",
     *     tags={"Role"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a role model",
     *     @OA\Parameter(
     *         description="Role ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Model ID",
     *         in="path",
     *         name="modelId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "selected_actions",
     *                     "selected_view_columns",
     *                     "selected_edit_columns",
     *                 },
     *                 @OA\Property(property="selected_actions", type="array", description="List of selected actions",
     *                     @OA\Items(type="string", description="Actiom name"),
     *                 ),
     *                 @OA\Property(property="selected_view_columns", type="array", description="List of selected columns for view",
     *                     @OA\Items(type="string", description="Column name"),
     *                 ),
     *                 @OA\Property(property="selected_edit_columns", type="array", description="List of selected columns for edit",
     *                     @OA\Items(type="string", description="Column name"),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(ref="#/components/schemas/RoleModelCollection")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
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
     * @throws AuthorizationException
     * @throws Exception
     */
    public function update(
        RoleModelUpdateRequest $request,
        int $id,
        int $modelId
    ): RoleModelResource {
        $role = $this->roleRepository->find($id);
        $this->authorize('edit', $role);

        $model = $this->modelRepository->find($modelId);
        $columns = Column::all(['id', 'name']);

        $role->permissions()->sync(
            Permission::query()->whereBelongsTo($model)
            ->whereHas('action', fn ($q) => $q->whereIn('name', $request->validated('selected_actions')))
            ->get(['id'])
        );

        $viewPermission = Permission::query()->whereBelongsTo($model)
            ->whereRelation('action', 'name', Action::NAMES['view'])->first(['id']);
        if ($viewPermission) {
            $viewColumns = [];
            foreach ($request->validated('selected_view_columns') as $item) {
                $column = $columns->where('name', $item)->first();
                $viewColumns[$column->id] = ['permission_id' => $viewPermission->id];
            }
            $role->columnsByPermission($viewPermission->id)->sync($viewColumns);
        }

        $editPermission = Permission::query()->whereBelongsTo($model)
            ->whereRelation('action', 'name', Action::NAMES['edit'])->first(['id']);
        if ($editPermission) {
            $editColumns = [];
            foreach ($request->validated('selected_edit_columns') as $item) {
                $column = $columns->where('name', $item)->first();
                $editColumns[$column->id] = ['permission_id' => $editPermission->id];
            }
            $role->columnsByPermission($editPermission->id)->sync($editColumns);
        }

        $resource = new RoleModelResource($model);
        $resource->role = $role;

        return $resource;
    }
}
