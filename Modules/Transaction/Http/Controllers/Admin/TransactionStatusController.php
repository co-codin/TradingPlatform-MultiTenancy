<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Transaction\Dto\TransactionStatusDto;
use Modules\Transaction\Http\Requests\TransactionStatusCreateRequest;
use Modules\Transaction\Http\Requests\TransactionStatusUpdateRequest;
use Modules\Transaction\Http\Resources\TransactionStatusResource;
use Modules\Transaction\Models\TransactionStatus;
use Modules\Transaction\Repositories\TransactionStatusRepository;
use Modules\Transaction\Services\TransactionStatusStorage;

final class TransactionStatusController extends Controller
{
    /**
     * @param TransactionStatusRepository $transactionStatusRepository
     * @param TransactionStatusStorage $transactionStatusStorage
     */
    public function __construct(
        protected TransactionStatusRepository $transactionStatusRepository,
        protected TransactionStatusStorage $transactionStatusStorage,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/transaction-statuses",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get transaction statuses",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/TransactionStatusCollection")
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
        $this->authorize('viewAny', TransactionStatus::class);

        $transactionStatuses = $this->transactionStatusRepository->jsonPaginate();

        return TransactionStatusResource::collection($transactionStatuses);
    }

    /**
     * @OA\Get(
     *     path="/admin/transaction-statuses/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get transaction status data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Transaction status ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/TransactionStatusResource")
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
        $transactionStatus = $this->transactionStatusRepository->find($id);

        $this->authorize('view', $transactionStatus);

        return new TransactionStatusResource($transactionStatus);
    }

    /**
     * @OA\Post(
     *     path="/admin/transaction-statuses",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new transaction status",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "title",
     *                 },
     *                 @OA\Property(property="name", description="Transaction status name"),
     *                 @OA\Property(property="title", type="string", description="Transaction status title"),
     *                 @OA\Property(property="is_active", type="boolean", description="Transaction status is active"),
     *                 @OA\Property(property="is_valid", type="boolean", description="Transaction status is valid"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionStatusResource")
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
    public function store(TransactionStatusCreateRequest $request): JsonResource
    {
        $this->authorize('create', TransactionStatus::class);

        $transactionStatus = $this->transactionStatusStorage->store(TransactionStatusDto::fromFormRequest($request));

        return new TransactionStatusResource($transactionStatus);
    }

    /**
     * @OA\Put(
     *     path="/admin/transaction-statuses/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a transaction status",
     *     @OA\Parameter(
     *         description="Transaction status ID",
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
     *                 },
     *                 @OA\Property(property="name", type="string", description="Transaction status name"),
     *                 @OA\Property(property="title", type="string", description="Transaction status title"),
     *                 @OA\Property(property="is_active", type="boolean", description="Transaction status is active"),
     *                 @OA\Property(property="is_valid", type="boolean", description="Transaction status is valid"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionStatusResource")
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
     *     path="/admin/transaction-statuses/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a transaction status",
     *     @OA\Parameter(
     *         description="Transaction status ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Transaction status name"),
     *                 @OA\Property(property="title", type="string", description="Transaction status title"),
     *                 @OA\Property(property="is_active", type="boolean", description="Transaction status is active"),
     *                 @OA\Property(property="is_valid", type="boolean", description="Transaction status is valid"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionStatusResource")
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
    public function update(TransactionStatusUpdateRequest $request, int $id): JsonResource
    {
        $transactionStatus = $this->transactionStatusRepository->find($id);

        $this->authorize('update', $transactionStatus);

        $transactionStatus = $this->transactionStatusStorage->update($transactionStatus, TransactionStatusDto::fromFormRequest($request));

        return new TransactionStatusResource($transactionStatus);
    }

    /**
     * @OA\Delete(
     *     path="/admin/transaction-statuses/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a transaction status",
     *     @OA\Parameter(
     *         description="Transaction status ID",
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
        $transactionStatus = $this->transactionStatusRepository->find($id);

        $this->authorize('delete', $transactionStatus);

        $this->transactionStatusStorage->destroy($transactionStatus);

        return response()->noContent();
    }
}
