<?php

declare(strict_types=1);

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Role\Dto\RoleDto;
use Modules\Role\Http\Requests\RoleCreateRequest;
use Modules\Role\Http\Requests\RoleUpdateRequest;
use Modules\Role\Http\Resources\RoleResource;
use Modules\Role\Models\Permission;
use Modules\Role\Models\Role;
use Modules\Role\Repositories\RoleRepository;
use Modules\Role\Services\RoleStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Spatie\QueryBuilder\QueryBuilderRequest;

final class RoleController extends Controller
{
    use ValidatesRequests;

    final public function __construct(
        protected RoleStorage $roleStorage,
        protected RoleRepository $roleRepository
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/roles/all",
     *      security={ {"sanctum": {} }},
     *      tags={"Role"},
     *      summary="Get roles list all",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/RoleCollection")
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
     * Display roles list all.
     *
     * @throws AuthorizationException
     */
    public function all(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Role::class);

        return RoleResource::collection(
            $this->roleRepository->scopes(['byBrand'])->all()
        );
    }

    /**
     * @OA\Get(
     *     path="/admin/roles",
     *     tags={"Role"},
     *     security={ {"sanctum": {} }},
     *     summary="Get roles",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/RoleCollection")
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
     * Index roles.
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    final public function index(): JsonResource
    {
        $this->authorize('viewAny', Role::class);

        $additional = array_intersect_key(
            ['all_permissions_count' => Permission::count()],
            array_flip(app(QueryBuilderRequest::class)->appends()->toArray())
        );

        return RoleResource::collection(
            $this->roleRepository->scopes(['byBrand'])->jsonPaginate()
        )->additional($additional);
    }

    /**
     * @OA\Get(
     *     path="/admin/roles/{id}",
     *     tags={"Role"},
     *     security={ {"sanctum": {} }},
     *     summary="Get role data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Role ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/RoleResource")
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
     * Show role.
     *
     * @param  int  $role
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    final public function show(int $role): JsonResource
    {
        $role = $this->roleRepository->find($role);

        $this->authorize('view', $role);

        $additional = array_intersect_key(
            ['all_permissions_count' => Permission::count()],
            array_flip(app(QueryBuilderRequest::class)->appends()->toArray())
        );

        return (new RoleResource($role))->additional($additional);
    }

    /**
     * @OA\Post(
     *     path="/admin/roles",
     *     tags={"Role"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new role",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "guard_name",
     *                     "key",
     *                     "permissions",
     *                 },
     *                 @OA\Property(property="name", description="Name of role"),
     *                 @OA\Property(property="guard_name", type="string", description="Guard name"),
     *                 @OA\Property(property="key", type="string", description="Key"),
     *                 @OA\Property(property="permissions", type="array", description="Array of permission`s ID",
     *                     @OA\Items(@OA\Property(property="id", type="integer")),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/RoleResource")
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
     * )
     *
     * Store role.
     *
     * @param  RoleCreateRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    final public function store(RoleCreateRequest $request): JsonResource
    {
        $this->authorize('create', Role::class);

        return new RoleResource(
            $this->roleStorage->store(RoleDto::fromFormRequest($request))
        );
    }

    /**
     * @OA\Put(
     *     path="/admin/roles/{id}",
     *     tags={"Role"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a role",
     *     @OA\Parameter(
     *         description="Role ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "guard_name",
     *                     "key",
     *                     "permissions",
     *                 },
     *                 @OA\Property(property="name", description="Name of role"),
     *                 @OA\Property(property="guard_name", type="string", description="Guard name"),
     *                 @OA\Property(property="key", type="string", description="Key"),
     *                 @OA\Property(property="permissions", type="array", description="Array of permission`s ID",
     *                     @OA\Items(@OA\Property(property="id", type="integer")),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/RoleResource")
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
     * ),
     * @OA\Patch(
     *     path="/admin/roles/{id}",
     *     tags={"Role"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a role",
     *     @OA\Parameter(
     *         description="Role ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", description="Name of role"),
     *                 @OA\Property(property="guard_name", type="string", description="Guard name"),
     *                 @OA\Property(property="key", type="string", description="Key"),
     *                 @OA\Property(property="permissions", type="array", description="Array of permission`s ID",
     *                     @OA\Items(@OA\Property(property="id", type="integer")),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/RoleResource")
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
     * Update role
     *
     * @param  int  $role
     * @param  RoleUpdateRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    final public function update(int $role, RoleUpdateRequest $request): JsonResource
    {
        $role = $this->roleRepository->find($role);

        $this->authorize('update', $role);

        return new RoleResource(
            $this->roleStorage->update($role, RoleDto::fromFormRequest($request))
        );
    }

    /**
     * @OA\Delete(
     *     path="/admin/roles/{id}",
     *     tags={"Role"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a role",
     *     @OA\Parameter(
     *         description="Role ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
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
     * Destroy role.
     *
     * @param  int  $role
     * @return Response
     *
     * @throws AuthorizationException
     */
    final public function destroy(int $role): Response
    {
        $role = $this->roleRepository->find($role);

        $this->authorize('delete', $role);

        $this->roleStorage->delete($role);

        return response()->noContent();
    }
}
