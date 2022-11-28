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
use Modules\Role\Models\Role;
use Modules\Role\Repositories\RoleRepository;
use Modules\Role\Services\RoleStorage;
use Modules\Role\Http\Resources\RoleResource;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class RoleController extends Controller
{
    use ValidatesRequests;

    final public function __construct(
        protected RoleStorage $roleStorage,
        protected RoleRepository $roleRepository
    ) {}

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
     * @throws AuthorizationException
     */
    final public function index(): JsonResource
    {
        $this->authorize('viewAny', Role::class);

        return RoleResource::collection(
            $this->roleRepository->jsonPaginate()
        );
    }

    /**
     * Show role.
     *
     * @param int $role
     * @return JsonResource
     * @throws AuthorizationException
     */
    final public function show(int $role): JsonResource
    {
        $role = $this->roleRepository->find($role);

        $this->authorize('view', $role);

        return new RoleResource($role);
    }

    /**
     * Store role.
     *
     * @param RoleCreateRequest $request
     * @return JsonResource
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
     * Update role
     *
     * @param int $role
     * @param RoleUpdateRequest $request
     * @return JsonResource
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
     * Destroy role.
     *
     * @param int $role
     * @return Response
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
