<?php

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\Role\Http\Resources\PermissionResource;
use Modules\Role\Repositories\PermissionRepository;

class PermissionController extends Controller
{
    public function __construct(
        protected PermissionRepository $permissionRepository
    ){}

    public function index()
    {
        $roles = $this->permissionRepository->jsonPaginate();

        return PermissionResource::collection($roles);
    }

    public function all()
    {
        $roles = $this->permissionRepository->all();

        return PermissionResource::collection($roles);
    }

    public function show(int $permission)
    {
        $permission = $this->permissionRepository->find($permission);

        return new PermissionResource($permission);
    }
}
