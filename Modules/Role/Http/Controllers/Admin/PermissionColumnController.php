<?php

declare(strict_types=1);

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\Role\Http\Requests\Column\PermissionColumnUpdateRequest;
use Modules\Role\Repositories\PermissionRepository;

final class PermissionColumnController extends Controller
{
    public function __construct(
        protected PermissionRepository $permissionRepository
    ) {
    }

    /**
     * @throws AuthorizationException
     */
    public function update(PermissionColumnUpdateRequest $request, int $id): void
    {
        $permission = $this->permissionRepository->find($id);

        $this->authorize('update', $permission);

        $ids = $request->collect('columns')->pluck('id')->filter()->unique();

        $permission->columns()->sync($ids);
    }
}
