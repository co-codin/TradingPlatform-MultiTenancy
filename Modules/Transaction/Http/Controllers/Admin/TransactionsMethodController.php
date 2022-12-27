<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Transaction\Dto\TransactionsMethodDto;
use Modules\Transaction\Http\Requests\TransactionsMethodCreateRequest;
use Modules\Transaction\Http\Requests\TransactionsMethodUpdateRequest;
use Modules\Transaction\Http\Resources\TransactionsMethodResource;
use Modules\Transaction\Models\TransactionsMethod;
use Modules\Transaction\Repositories\TransactionsMethodRepository;
use Modules\Transaction\Services\TransactionsMethodStorage;

final class TransactionsMethodController extends Controller
{
    /**
     * @param TransactionsMethodRepository $transactionsMethodRepository
     * @param TransactionsMethodStorage $transactionsMethodStorage
     */
    public function __construct(
        protected TransactionsMethodRepository $transactionsMethodRepository,
        protected TransactionsMethodStorage $transactionsMethodStorage,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/transaction-methods",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get transaction method",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/TransactionsMethodCollection")
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
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', TransactionsMethod::class);

        $transactionsMethod = $this->transactionsMethodRepository->jsonPaginate();

        return TransactionsMethodResource::collection($transactionsMethod);
    }

    /**
     * @OA\Get(
     *     path="/admin/transaction-methods/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get transaction method data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Transaction method ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/TransactionsMethodResource")
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
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $transactionsMethod = $this->transactionsMethodRepository->find($id);

        $this->authorize('view', $transactionsMethod);

        return new TransactionsMethodResource($transactionsMethod);
    }

    /**
     * @OA\Post(
     *     path="/admin/transaction-methods",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new transaction method",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "title",
     *                     "is_active",
     *                 },
     *                 @OA\Property(property="name", description="Transaction method name"),
     *                 @OA\Property(property="title", type="string", description="Transaction method title"),
     *                 @OA\Property(property="is_active", type="boolean", description="Transaction method is active"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionsMethodResource")
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
     * @throws AuthorizationException
     * @throws Exception
     */
    public function store(TransactionsMethodCreateRequest $request): JsonResource
    {
        $this->authorize('create', TransactionsMethod::class);

        $transactionsMethod = $this->transactionsMethodStorage->store(TransactionsMethodDto::fromFormRequest($request));

        return new TransactionsMethodResource($transactionsMethod);
    }

    /**
     * @OA\Put(
     *     path="/admin/transaction-methods/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a transaction method",
     *     @OA\Parameter(
     *         description="Transaction method ID",
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
     *                     "title",
     *                     "is_active",
     *                 },
     *                 @OA\Property(property="name", description="Transaction method name"),
     *                 @OA\Property(property="title", type="string", description="Transaction method title"),
     *                 @OA\Property(property="is_active", type="boolean", description="Transaction method is active"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionsMethodResource")
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
     *     path="/admin/transaction-methods/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a transaction method",
     *     @OA\Parameter(
     *         description="Transaction method ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", description="Transaction method name"),
     *                 @OA\Property(property="title", type="string", description="Transaction method title"),
     *                 @OA\Property(property="is_active", type="boolean", description="Transaction method is active"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionsMethodResource")
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
    public function update(TransactionsMethodUpdateRequest $request, int $id): JsonResource
    {
        $transactionsMethod = $this->transactionsMethodRepository->find($id);

        $this->authorize('update', $transactionsMethod);

        $transactionsMethod = $this->transactionsMethodStorage->update($transactionsMethod, TransactionsMethodDto::fromFormRequest($request));

        return new TransactionsMethodResource($transactionsMethod);
    }

    /**
     * @OA\Delete(
     *     path="/admin/transaction-methods/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a transaction method",
     *     @OA\Parameter(
     *         description="Transaction method ID",
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
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(int $id): Response
    {
        $transactionsMethod = $this->transactionsMethodRepository->find($id);

        $this->authorize('delete', $transactionsMethod);

        $this->transactionsMethodStorage->destroy($transactionsMethod);

        return response()->noContent();
    }
}
