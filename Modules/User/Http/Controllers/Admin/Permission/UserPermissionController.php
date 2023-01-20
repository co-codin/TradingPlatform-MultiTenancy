<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Modules\Role\Repositories\PermissionRepository;
use Modules\User\Http\Requests\Permission\PermissionColumnsRequest;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserBanService;
use Modules\User\Services\UserBatchService;
use Modules\User\Services\UserStorage;
use OpenApi\Annotations as OA;

final class UserPermissionController extends Controller
{
    public function __construct(
        protected UserStorage $userStorage,
        protected UserRepository $userRepository,
        protected UserBanService $userBanService,
        protected UserBatchService $userBatchService,
    ) {
    }

    /**
     * @OA\Put(
     *     path="/admin/workers/{id}/permission/{permissionId}/columns",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a worker permission columns",
     *     @OA\Parameter(
     *         description="Worker ID",
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
    public function columns(
        PermissionColumnsRequest $request,
        int $id,
        int $permissionId,
        PermissionRepository $permissionRepository
    ): array {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        $this->authorize('edit', $user);

        $permission = $permissionRepository->findOrFail($permissionId);

        $columns = [];
        foreach ($request->validated('columns') as $item) {
            $columns[] = ['column_id' => $item, 'permission_id' => $permissionId];
        }

        return $user->columnsByPermission($permission->id)->sync($columns);
    }
}
