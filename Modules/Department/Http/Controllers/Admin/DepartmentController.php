<?php

namespace Modules\Department\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Department\Dto\DepartmentDto;
use Modules\Department\Http\Requests\DepartmentStoreRequest;
use Modules\Department\Http\Requests\DepartmentUpdateRequest;
use Modules\Department\Http\Resources\DepartmentResource;
use Modules\Department\Models\Department;
use Modules\Department\Repositories\DepartmentRepository;
use Modules\Department\Services\DepartmentStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class DepartmentController extends Controller
{
    /**
     * @param DepartmentRepository $departmentRepository
     * @param DepartmentStorage $storage
     */
    public function __construct(
        protected DepartmentRepository $departmentRepository,
        protected DepartmentStorage    $storage,
    )
    {
        //
    }

    public function all(): JsonResource
    {
        $this->authorize('viewAny');

        $departments = $this->departmentRepository->all();

        return DepartmentResource::collection($departments);
    }

    /**
     * @OA\Get(
     *      path="/admin/departments",
     *      operationId="departments.index",
     *      security={ {"sanctum": {} }},
     *      tags={"Department"},
     *      summary="Get departments list",
     *      description="Returns departments list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DepartmentCollection")
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
     * Display departments list.
     *
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Department::class);

        return DepartmentResource::collection($this->departmentRepository->jsonPaginate());
    }

    /**
     * @OA\Post(
     *      path="/admin/departments",
     *      operationId="departments.store",
     *      security={ {"sanctum": {} }},
     *      tags={"Department"},
     *      summary="Store department",
     *      description="Returns department data.",
     *      @OA\Parameter(
     *          description="Name",
     *          in="query",
     *          name="name",
     *          required=true,
     *          example="Name"
     *      ),
     *      @OA\Parameter(
     *          description="Title",
     *          in="query",
     *          name="title",
     *          required=true,
     *          example="Title"
     *      ),
     *      @OA\Parameter(
     *          description="Is active",
     *          in="query",
     *          name="is_active",
     *          required=false,
     *          example=1
     *      ),
     *      @OA\Parameter(
     *          description="Is default",
     *          in="query",
     *          name="is_default",
     *          required=false,
     *          example=1
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DepartmentResource")
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
     * Store department.
     *
     * @param DepartmentStoreRequest $request
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(DepartmentStoreRequest $request): JsonResource
    {
        $this->authorize('create', Department::class);

        return new DepartmentResource(
            $this->storage->store(DepartmentDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/departments/{id}",
     *      operationId="departments.show",
     *      security={ {"sanctum": {} }},
     *      tags={"Department"},
     *      summary="Get department",
     *      description="Returns department data.",
     *      @OA\Parameter(
     *          description="Department id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DepartmentResource")
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
     * Show the department.
     *
     * @param int $id
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $department = $this->departmentRepository->find($id);

        $this->authorize('view', $department);

        return new DepartmentResource($department);
    }

    /**
     * @OA\Patch(
     *      path="/admin/departments/{id}",
     *      operationId="departments.update",
     *      security={ {"sanctum": {} }},
     *      tags={"Department"},
     *      summary="Update department",
     *      description="Returns department data.",
     *      @OA\Parameter(
     *          description="Department id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Parameter(
     *          description="Name",
     *          in="query",
     *          name="name",
     *          required=false,
     *          example="Name"
     *      ),
     *      @OA\Parameter(
     *          description="Title",
     *          in="query",
     *          name="title",
     *          required=false,
     *          example="Title"
     *      ),
     *      @OA\Parameter(
     *          description="Is active",
     *          in="query",
     *          name="is_active",
     *          required=false,
     *          example=1
     *      ),
     *      @OA\Parameter(
     *          description="Is default",
     *          in="query",
     *          name="is_default",
     *          required=false,
     *          example=1
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DepartmentResource")
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
     * Update the department.
     *
     * @param DepartmentUpdateRequest $request
     * @param int $id
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(DepartmentUpdateRequest $request, int $id): JsonResource
    {
        $department = $this->departmentRepository->find($id);

        $this->authorize('update', $department);

        return new DepartmentResource(
            $this->storage->update(
                $department,
                DepartmentDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/departments/{id}",
     *      operationId="departments.delete",
     *      security={ {"sanctum": {} }},
     *      tags={"Department"},
     *      summary="Delete department",
     *      description="Returns status.",
     *      @OA\Parameter(
     *          description="Department id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/DepartmentResource")
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
     * Remove the department.
     *
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $department = $this->departmentRepository->find($id);

        $this->authorize('delete', $department);

        $this->storage->delete($department);

        return response()->noContent();
    }
}
