<?php

declare(strict_types=1);

namespace Modules\Role\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Modules\Role\Dto\ColumnDto;
use Modules\Role\Http\Requests\Column\ColumnCreateRequest;
use Modules\Role\Http\Requests\Column\ColumnUpdateRequest;
use Modules\Role\Http\Resources\ColumnResource;
use Modules\Role\Models\Column;
use Modules\Role\Repositories\ColumnRepository;
use Modules\Role\Services\ColumnStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class ColumnController extends Controller
{
    public function __construct(
        protected ColumnStorage $columnStorage,
        protected ColumnRepository $columnRepository
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/permissions/columns",
     *     tags={"Permission"},
     *     security={ {"sanctum": {} }},
     *     summary="Get columns",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/ColumnCollection")
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
     * Index columns.
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Column::class);

        return ColumnResource::collection(
            $this->columnRepository->jsonPaginate()
        );
    }

    /**
     * @OA\Get(
     *     path="/admin/permissions/columns/{id}",
     *     tags={"Permission"},
     *     security={ {"sanctum": {} }},
     *     summary="Get column data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Column ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/ColumnResource")
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
     * Show column.
     *
     * @param  int  $id
     * @return ColumnResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): ColumnResource
    {
        $column = $this->columnRepository->find($id);

        $this->authorize('view', $column);

        return new ColumnResource($column);
    }

    /**
     * @OA\Post(
     *     path="/admin/permissions/columns",
     *     tags={"Permission"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new сolumn",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(property="name", description="Name of column"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/ColumnResource")
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
     * Store column.
     *
     * @param  ColumnCreateRequest  $request
     * @return ColumnResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(ColumnCreateRequest $request): ColumnResource
    {
        $this->authorize('create', Column::class);

        return new ColumnResource(
            $this->columnStorage->store(ColumnDto::fromFormRequest($request))
        );
    }

    /**
     * @OA\Put(
     *     path="/admin/permissions/columns/{id}",
     *     tags={"Permission"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a сolumn",
     *     @OA\Parameter(
     *         description="Column ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(property="name", description="Name of column"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/ColumnResource")
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
     *     path="/admin/permissions/columns/{id}",
     *     tags={"Permission"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a column",
     *     @OA\Parameter(
     *         description="Column ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", description="Name of column"),
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
     *         @OA\JsonContent(ref="#/components/schemas/ColumnResource")
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
     * Update column
     *
     * @param  int  $id
     * @param  ColumnUpdateRequest  $request
     * @return ColumnResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(int $id, ColumnUpdateRequest $request): ColumnResource
    {
        $column = $this->columnRepository->find($id);

        $this->authorize('update', $column);

        return new ColumnResource(
            $this->columnStorage->update($column, ColumnDto::fromFormRequest($request))
        );
    }

    /**
     * @OA\Delete(
     *     path="/admin/permissions/columns/{id}",
     *     tags={"Permission"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a column",
     *     @OA\Parameter(
     *         description="Column ID",
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
     * Destroy column.
     *
     * @param  int  $id
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $column = $this->columnRepository->find($id);

        $this->authorize('delete', $column);

        $this->columnStorage->delete($column);

        return response()->noContent();
    }
}
