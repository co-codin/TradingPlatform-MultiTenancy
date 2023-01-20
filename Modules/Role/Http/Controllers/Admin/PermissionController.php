<?php

declare(strict_types=1);

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Role\Http\Resources\PermissionResource;
use Modules\Role\Models\Permission;
use Modules\Role\Repositories\PermissionRepository;
use OpenApi\Annotations as OA;

final class PermissionController extends Controller
{
    public function __construct(
        protected PermissionRepository $permissionRepository
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/permissions",
     *     tags={"Permission"},
     *     security={ {"sanctum": {} }},
     *     summary="Get permissions",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/PermissionCollection")
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
     * Index permissions.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Permission::class);

        $roles = $this->permissionRepository->jsonPaginate();

        return PermissionResource::collection($roles);
    }

    /**
     * @OA\Get(
     *     path="/admin/permissions/all",
     *     tags={"Permission"},
     *     security={ {"sanctum": {} }},
     *     summary="Get all permissions",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/PermissionCollection")
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
     * Index permissions list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function all(): JsonResource
    {
        $this->authorize('viewAny', Permission::class);

        $roles = $this->permissionRepository->all();

        return PermissionResource::collection($roles);
    }

    /**
     * @OA\Get(
     *     path="/admin/permissions/{id}",
     *     tags={"Permission"},
     *     security={ {"sanctum": {} }},
     *     summary="Get permission data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Permission ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/PermissionResource")
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
     * View permission.
     *
     * @param  int  $permission
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $permission): JsonResource
    {
        $this->authorize('view', Permission::class);

        $permission = $this->permissionRepository->find($permission);

        return new PermissionResource($permission);
    }

    /**
     * @OA\Get(
     *     path="/admin/permissions/count",
     *     tags={"Permission"},
     *     security={ {"sanctum": {} }},
     *     summary="Get permissions count",
     *     @OA\Response(
     *          response=200,
     *          description="success",
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
     * Permission count.
     *
     * @return JsonResponse
     */
    public function count(): JsonResponse
    {
        return response()->json([
            'count' => $this->permissionRepository->count(),
        ]);
    }
}
