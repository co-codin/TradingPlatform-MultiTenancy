<?php

declare(strict_types=1);

namespace Modules\Sale\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Sale\Dto\SaleStatusDto;
use Modules\Sale\Http\Requests\SaleStatusStoreRequest;
use Modules\Sale\Http\Resources\SaleStatusResource;
use Modules\Sale\Models\SaleStatus;
use Modules\Sale\Repositories\SaleStatusRepository;
use Modules\Sale\Services\SaleStatusStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class SaleStatusController extends Controller
{
    /**
     * @param  SaleStatusRepository  $repository
     * @param  SaleStatusStorage  $storage
     */
    public function __construct(
        protected SaleStatusRepository $repository,
        protected SaleStatusStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/sale-statuses",
     *      security={ {"sanctum": {} }},
     *      tags={"SaleStatus"},
     *      summary="Get salestatus list",
     *      description="Returns salestatus list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SaleStatusCollection")
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
     * Display salestatus list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', SaleStatus::class);

        return SaleStatusResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Post(
     *      path="/admin/sale-statuses",
     *      security={ {"sanctum": {} }},
     *      tags={"SaleStatus"},
     *      summary="Store salestatus",
     *      description="Returns salestatus data.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "title",
     *                     "color",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Name of sale status"),
     *                 @OA\Property(property="title", type="string", description="Title of sale status"),
     *                 @OA\Property(property="color", type="string", description="RGB HEX", example="#e1e1e1"),
     *                 @OA\Property(property="department_id", type="integer", example="1"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SaleStatusResource")
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
     * Store salestatus.
     *
     * @param  SaleStatusStoreRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(SaleStatusStoreRequest $request): JsonResource
    {
        $this->authorize('create', SaleStatus::class);

        return new SaleStatusResource(
            $this->storage->store(SaleStatusDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/sale-statuses/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"SaleStatus"},
     *      summary="Get salestatus",
     *      description="Returns salestatus data.",
     *      @OA\Parameter(
     *         description="Sale statuses ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SaleStatusResource")
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
     * Show the salestatus.
     *
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $saleStatus = $this->repository->find($id);

        $this->authorize('view', $saleStatus);

        return new SaleStatusResource($saleStatus);
    }

    /**
     * @OA\Put(
     *      path="/admin/sale-statuses/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"SaleStatus"},
     *      summary="Update salestatus",
     *      description="Returns salestatus data.",
     *      @OA\Parameter(
     *         description="Sale statuses ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "title",
     *                     "color",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Name of sale status"),
     *                 @OA\Property(property="title", type="string", description="Title of sale status"),
     *                 @OA\Property(property="color", type="string", description="RGB HEX", example="#e1e1e1"),
     *                 @OA\Property(property="department_id", type="integer", example="1"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SaleStatusResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * ),
     * @OA\Patch(
     *      path="/admin/sale-statuses/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"SaleStatus"},
     *      summary="Update salestatus",
     *      description="Returns salestatus data.",
     *      @OA\Parameter(
     *         description="Sale statuses ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Name of sale status"),
     *                 @OA\Property(property="title", type="string", description="Title of sale status"),
     *                 @OA\Property(property="color", type="string", description="RGB HEX", example="#e1e1e1"),
     *                 @OA\Property(property="department_id", type="integer", example="1"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SaleStatusResource")
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
     * Update the salestatus.
     *
     * @param  SaleStatusStoreRequest  $request
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(SaleStatusStoreRequest $request, int $id): JsonResource
    {
        $saleStatus = $this->repository->find($id);

        $this->authorize('update', $saleStatus);

        return new SaleStatusResource(
            $this->storage->update(
                $saleStatus,
                SaleStatusDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/sale-statuses/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"SaleStatus"},
     *      summary="Delete salestatus",
     *      description="Returns status.",
     *      @OA\Parameter(
     *         description="Sale statuses ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SaleStatusResource")
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
     * Remove the salestatus.
     *
     * @param  int  $id
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $saleStatus = $this->repository->find($id);

        $this->authorize('delete', $saleStatus);

        $this->storage->delete($saleStatus);

        return response()->noContent();
    }
}
