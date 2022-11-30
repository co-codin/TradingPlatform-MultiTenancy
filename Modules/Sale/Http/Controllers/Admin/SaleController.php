<?php

namespace Modules\Sale\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Sale\Dto\SaleDto;
use Modules\Sale\Http\Resources\SaleResource;
use Modules\Sale\Models\SaleStatus;
use Modules\Sale\Repositories\SaleRepository;
use Modules\Sale\Http\Requests\SaleStoreRequest;
use Modules\Sale\Services\SaleStorage;
use Illuminate\Http\Response;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class SaleController extends Controller
{
    /**
     * @param SaleRepository $repository
     * @param SaleStorage $storage
     */
    public function __construct(
        protected SaleRepository $repository,
        protected SaleStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/sale",
     *      operationId="sale.index",
     *      security={ {"sanctum": {} }},
     *      tags={"Sale"},
     *      summary="Get sale list",
     *      description="Returns sale list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SaleCollection")
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
     * Display sale list.
     *
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', SaleStatus::class);

        return SaleResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Post(
     *      path="/admin/sale",
     *      operationId="sale.store",
     *      security={ {"sanctum": {} }},
     *      tags={"Sale"},
     *      summary="Store sale",
     *      description="Returns sale data.",
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
     *          @OA\JsonContent(ref="#/components/schemas/SaleResource")
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
     * Store sale.
     *
     * @param SaleStoreRequest $request
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(SaleStoreRequest $request): JsonResource
    {
        $this->authorize('create', SaleStatus::class);

        return new SaleResource(
            $this->storage->store(SaleDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/sale/{id}",
     *      operationId="sale.show",
     *      security={ {"sanctum": {} }},
     *      tags={"Sale"},
     *      summary="Get sale",
     *      description="Returns sale data.",
     *      @OA\Parameter(
     *          description="Sale id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SaleResource")
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
     * Show the sale.
     *
     * @param int $id
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $sale = $this->repository->find($id);

        $this->authorize('view', $sale);

        return new SaleResource($sale);
    }

    /**
     * @OA\Patch(
     *      path="/admin/sale/{id}",
     *      operationId="sale.update",
     *      security={ {"sanctum": {} }},
     *      tags={"Sale"},
     *      summary="Update sale",
     *      description="Returns sale data.",
     *      @OA\Parameter(
     *          description="Sale id",
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
     *          example="Russia"
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
     *          @OA\JsonContent(ref="#/components/schemas/SaleResource")
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
     * Update the sale.
     *
     * @param SaleStoreRequest $request
     * @param int $id
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(SaleStoreRequest $request, int $id): JsonResource
    {
        $sale = $this->repository->find($id);

        $this->authorize('update', $sale);

        return new SaleResource(
            $this->storage->update(
                $sale,
                SaleDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/sale/{id}",
     *      operationId="sale.delete",
     *      security={ {"sanctum": {} }},
     *      tags={"Sale"},
     *      summary="Delete sale",
     *      description="Returns status.",
     *      @OA\Parameter(
     *          description="Sale id",
     *          in="path",
     *          name="id",
     *          required=true,
     *          example=1
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SaleResource")
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
     * Remove the sale.
     *
     * @param int $id
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $sale = $this->repository->find($id);

        $this->authorize('delete', $sale);

        $this->storage->delete($sale);

        return response()->noContent();
    }
}
