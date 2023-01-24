<?php

declare(strict_types=1);

namespace Modules\Role\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Role\Http\Requests\Permission\RolePermissionColumnsRequest;
use Modules\Role\Http\Resources\ColumnResource;
use Modules\Role\Models\Role;
use Modules\Role\Repositories\PermissionRepository;
use Modules\Role\Repositories\RoleRepository;
use OpenApi\Annotations as OA;

final class RolePermissionController extends Controller
{
    public function __construct(
        private readonly RoleRepository $roleRepository,
        private readonly PermissionRepository $permissionRepository,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/roles/{id}/permission/{permissionId}/columns",
     *     tags={"Role"},
     *     security={ {"sanctum": {} }},
     *     summary="Get a role permission columns",
     *     @OA\Parameter(
     *         description="Role ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Permission ID",
     *         in="path",
     *         name="permissionId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/ColumnCollection")
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
    public function getColumns(int $id, int $permissionId): AnonymousResourceCollection
    {
        /** @var Role $role */
        $role = $this->roleRepository->find($id);
        $this->authorize('view', $role);
        $permission = $this->permissionRepository->findOrFail($permissionId);

        return ColumnResource::collection($role->columnsByPermission($permission->id)->get()->makeHidden('pivot'));
    }

    /**
     * @OA\Put(
     *     path="/admin/roles/{id}/permission/{permissionId}/columns",
     *     tags={"Role"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a role permission columns",
     *     @OA\Parameter(
     *         description="Role ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Permission ID",
     *         in="path",
     *         name="permissionId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "columns",
     *                 },
     *                 @OA\Property(property="columns", type="array", description="Array of columns ID",
     *                     @OA\Items(type="integer", description="Column ID"),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Success"
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
    public function syncColumns(
        RolePermissionColumnsRequest $request,
        int $id,
        int $permissionId
    ): array {
        /** @var Role $role */
        $role = $this->roleRepository->find($id);
        $this->authorize('edit', $role);
        $permission = $this->permissionRepository->findOrFail($permissionId);

        $columns = [];
        foreach ($request->validated('columns') as $item) {
            $columns[$item] = ['permission_id' => $permission->id];
        }

        return $role->columnsByPermission($permission->id)->sync($columns);
    }
}
