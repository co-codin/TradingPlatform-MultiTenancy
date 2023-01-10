<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Transaction\Dto\TransactionDto;
use Modules\Transaction\Http\Requests\TransactionCreateRequest;
use Modules\Transaction\Http\Requests\TransactionUpdateBatchRequest;
use Modules\Transaction\Http\Requests\TransactionUpdateRequest;
use Modules\Transaction\Http\Resources\TransactionResource;
use Modules\Transaction\Repositories\TransactionRepository;
use Modules\Transaction\Services\TransactionBatchService;
use Modules\Transaction\Services\TransactionStorage;
use OpenApi\Annotations as OA;

final class TransactionController extends Controller
{
    /**
     * @param  TransactionStorage  $transactionStorage
     * @param  TransactionRepository  $transactionRepository
     * @param  TransactionBatchService  $transactionBatchService
     */
    public function __construct(
        protected TransactionStorage $transactionStorage,
        protected TransactionRepository $transactionRepository,
        protected TransactionBatchService $transactionBatchService,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/transactions",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get transactions",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/TransactionCollection")
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
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return TransactionResource::collection($this->transactionRepository->jsonPaginate());
    }

    /**
     * @OA\Get(
     *     path="/admin/transactions/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get transaction data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Transaction ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/TransactionResource")
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
     * @return TransactionResource
     */
    public function show(int $id): TransactionResource
    {
        return new TransactionResource(
            $this->transactionRepository->find($id),
        );
    }

    /**
     * @OA\Post(
     *     path="/admin/transactions",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new transaction",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionResource")
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
     * @param  TransactionCreateRequest  $request
     * @return TransactionResource
     *
     * @throws Exception
     */
    public function store(TransactionCreateRequest $request): TransactionResource
    {
        return new TransactionResource(
            $this->transactionStorage->store(TransactionDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Put(
     *     path="/admin/transactions/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a transaction",
     *     @OA\Parameter(
     *         description="Transaction ID",
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
     *                     "amount",
     *                     "method_id",
     *                     "is_test",
     *                     "worker_id",
     *                     "status_id",
     *                     "external_id",
     *                 },
     *                 @OA\Property(property="amount", type="float", description="Amount of transaction"),
     *                 @OA\Property(property="method_id", type="integer", description="Method ID"),
     *                 @OA\Property(property="is_test", type="boolean", description="Is test"),
     *                 @OA\Property(property="worker_id", type="integer", description="Worker ID"),
     *                 @OA\Property(property="status_id", type="integer", description="Status ID"),
     *                 @OA\Property(property="external_id", type="string", description="External ID"),
     *                 @OA\Property(property="description", type="string", description="Description"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionResource")
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
     *     path="/admin/transactions/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a transaction",
     *     @OA\Parameter(
     *         description="Transaction ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="amount", type="float", description="Amount of transaction"),
     *                 @OA\Property(property="method_id", type="integer", description="Method ID"),
     *                 @OA\Property(property="is_test", type="boolean", description="Is test"),
     *                 @OA\Property(property="worker_id", type="integer", description="Worker ID"),
     *                 @OA\Property(property="status_id", type="integer", description="Status ID"),
     *                 @OA\Property(property="external_id", type="string", description="External ID"),
     *                 @OA\Property(property="description", type="string", description="Description"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionResource")
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
     * @param  TransactionUpdateRequest  $request
     * @param  int  $id
     * @return TransactionResource
     *
     * @throws Exception
     */
    public function update(TransactionUpdateRequest $request, int $id): TransactionResource
    {
        return new TransactionResource(
            $this->transactionStorage->update(
                $this->transactionRepository->find($id),
                TransactionDto::fromFormRequest($request),
            ),
        );
    }

    /**
     * @OA\Patch (
     *     path="/admin/transactions/update/batch",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Batch transaction update",
     *     @OA\RequestBody(
     *        @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                required={"transactions"},
     *                @OA\Property(property="transactions", type="array",
     *                    @OA\Items(required={"id"},
     *                        @OA\Property(property="id", type="integer", description="Transaction ID"),
     *                        @OA\Property(property="status_id", type="integer", description="Status ID"),
     *                        @OA\Property(property="worker_id", type="integer", description="Worker ID"),
     *                        @OA\Property(property="is_test", type="boolean", description="Is test"),
     *                        @OA\Property(property="method_id", type="integer", description="Method ID"),
     *                    )
     *                )
     *            ),
     *        ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionResource")
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
     * Update batch transaction.
     *
     * @param  TransactionUpdateBatchRequest  $request
     * @return JsonResource
     *
     * @throws Exception
     */
    public function updateBatch(TransactionUpdateBatchRequest $request): JsonResource
    {
        $transactions = $this->transactionBatchService->setAuthUser($request->user())->updateBatch($request->validated('transactions', []));

        return TransactionResource::collection($transactions);
    }
}
