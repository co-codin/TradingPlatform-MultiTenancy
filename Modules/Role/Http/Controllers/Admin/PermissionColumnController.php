<?php

declare(strict_types=1);

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\Role\Http\Requests\Column\PermissionColumnUpdateRequest;
use Modules\Role\Repositories\PermissionRepository;
use OpenApi\Annotations as OA;

final class PermissionColumnController extends Controller
{
    public function __construct(
        protected PermissionRepository $permissionRepository
    ) {
    }

    /**
     * @OA\Put(
     *     path="/admin/permissions/{id}/columns",
     *     tags={"Permission"},
     *     security={ {"sanctum": {} }},
     *     summary="Update permission columns",
     *     @OA\Parameter(
     *         description="Permission id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"columns"},
     *                 @OA\Property(property="columns", type="array", @OA\Items(required={"id"}, @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     description="Column id",
     *                 ))),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok"
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
     */
    public function update(PermissionColumnUpdateRequest $request, int $id): void
    {
        $permission = $this->permissionRepository->find($id);

        $this->authorize('update', $permission);

        $ids = $request->collect('columns')->pluck('id')->filter()->unique();

        $permission->columns()->sync($ids);
    }
}
