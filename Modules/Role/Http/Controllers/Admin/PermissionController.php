<?php

declare(strict_types=1);

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Role\Http\Resources\PermissionResource;
use Modules\Role\Models\Permission;
use Modules\Role\Repositories\PermissionRepository;

final class PermissionController extends Controller
{
    /**
     * @param PermissionRepository $permissionRepository
     */
    final public function __construct(
        protected PermissionRepository $permissionRepository
    ){}

    /**
     * Index permissions.
     *
     * @return JsonResource
     * @throws AuthorizationException
     */
    final public function index(): JsonResource
    {
        $this->authorize('viewAny', Permission::class);

        $roles = $this->permissionRepository->jsonPaginate();

        return PermissionResource::collection($roles);
    }

    /**
     * Index permissions list.
     *
     * @return JsonResource
     * @throws AuthorizationException
     */
    final public function all(): JsonResource
    {
        $this->authorize('viewAny', Permission::class);

        $roles = $this->permissionRepository->all();

        return PermissionResource::collection($roles);
    }

    /**
     * View permission.
     *
     * @param int $permission
     * @return JsonResource
     * @throws AuthorizationException
     */
    final public function show(int $permission): JsonResource
    {
        $this->authorize('view', Permission::class);

        $permission = $this->permissionRepository->find($permission);

        return new PermissionResource($permission);
    }
}
