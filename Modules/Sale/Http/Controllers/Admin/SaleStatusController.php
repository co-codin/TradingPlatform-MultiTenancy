<?php

namespace Modules\Sale\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Sale\Dto\SaleStatusDto;
use Modules\Sale\Http\Resources\SaleStatusResource;
use Modules\Sale\Models\SaleStatus;
use Modules\Sale\Repositories\SaleStatusRepository;
use Modules\Sale\Http\Requests\SaleStatusStoreRequest;
use Modules\Sale\Services\SaleStatusStorage;
use Illuminate\Http\Response;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SaleStatusController extends Controller
{
    /**
     * @param SaleStatusRepository $repository
     * @param SaleStatusStorage $storage
     */
    public function __construct(
        protected SaleStatusRepository $repository,
        protected SaleStatusStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/salestatus",
     *      operationId="salestatus.index",
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
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', SaleStatus::class);

        return SaleStatusResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Post(
     *      path="/admin/salestatus",
     *      operationId="salestatus.store",
     *      security={ {"sanctum": {} }},
     *      tags={"SaleStatus"},
     *      summary="Store salestatus",
     *      description="Returns salestatus data.",
     *      @OA\Parameter(
     *          description="Name",
     *          in="query",
     *          name="name",
     *          required=true,
     *          example="Any name"
     *      ),
     *      @OA\Parameter(
     *          description="Title",
     *          in="query",
     *          name="title",
     *          required=true,
     *          example="Any title"
     *      ),
     *       @OA\Parameter(
     *          description="Color",
     *          in="query",
     *          name="color",
     *          required=true,
     *          example="#e1e1e1"
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
     * @param SaleStatusStoreRequest $request
     * @return JsonResource
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
     *      path="/admin/salestatus/{id}",
     *      operationId="salestatus.show",
     *      security={ {"sanctum": {} }},
     *      tags={"SaleStatus"},
     *      summary="Get salestatus",
     *      description="Returns salestatus data.",
     *      @OA\Parameter(
     *          description="SaleStatus id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
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
     * @param int $id
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $salestatus = $this->repository->find($id);

        $this->authorize('view', $salestatus);

        return new SaleStatusResource($salestatus);
    }

    /**
     * @OA\Patch(
     *      path="/admin/salestatus/{id}",
     *      operationId="salestatus.update",
     *      security={ {"sanctum": {} }},
     *      tags={"SaleStatus"},
     *      summary="Update salestatus",
     *      description="Returns salestatus data.",
     *      @OA\Parameter(
     *          description="SaleStatus id",
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
     *          example="Any name"
     *      ),
     *      @OA\Parameter(
     *          description="Title",
     *          in="query",
     *          name="title",
     *          required=true,
     *          example="Any title"
     *      ),
     *      @OA\Parameter(
     *          description="Color",
     *          in="query",
     *          name="color",
     *          required=true,
     *          example="#e1e1e1"
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
     * @param SaleStatusStoreRequest $request
     * @param int $id
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(SaleStatusStoreRequest $request, int $id): JsonResource
    {
        $salestatus = $this->repository->find($id);

        $this->authorize('update', $salestatus);

        return new SaleStatusResource(
            $this->storage->update(
                $salestatus,
                SaleStatusDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/salestatus/{id}",
     *      operationId="salestatus.delete",
     *      security={ {"sanctum": {} }},
     *      tags={"SaleStatus"},
     *      summary="Delete salestatus",
     *      description="Returns status.",
     *      @OA\Parameter(
     *          description="SaleStatus id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
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
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $salestatus = $this->repository->find($id);

        $this->authorize('delete', $salestatus);

        $this->storage->delete($salestatus);

        return response()->noContent();
    }
}
