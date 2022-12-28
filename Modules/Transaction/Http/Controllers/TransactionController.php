<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Transaction\Dto\TransactionDto;
use Modules\Transaction\Http\Requests\TransactionCreateRequest;
use Modules\Transaction\Http\Requests\TransactionUpdateRequest;
use Modules\Transaction\Http\Resources\TransactionResource;
use Modules\Transaction\Models\Transaction;
use Modules\Transaction\Repositories\TransactionRepository;
use Modules\Transaction\Services\TransactionStorage;

final class TransactionController extends Controller
{
    /**
     * @param TransactionStorage $transactionStorage
     * @param TransactionRepository $transactionRepository
     */
    public function __construct(
        protected TransactionStorage $transactionStorage,
        protected TransactionRepository $transactionRepository,
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
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Transaction::class);

        $transactions = $this->transactionRepository->jsonPaginate();

        return TransactionResource::collection($transactions);
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
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $transaction = $this->transactionRepository->find($id);

        $this->authorize('view', $transaction);

        return new TransactionResource($transaction);
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
     * @param TransactionCreateRequest $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function store(TransactionCreateRequest $request): JsonResource
    {
        $this->authorize('create', Transaction::class);

        $transaction = $this->transactionStorage->store(TransactionDto::fromFormRequest($request));

        return new TransactionResource($transaction);
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
     * @param TransactionUpdateRequest $request
     * @param int $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function update(TransactionUpdateRequest $request, int $id): JsonResource
    {
        $transaction = $this->transactionRepository->find($id);

        $this->authorize('update', $transaction);

        $transaction = $this->transactionStorage->update($transaction, TransactionDto::fromFormRequest($request));

        return new TransactionResource($transaction);
    }

    /**
     * @OA\Delete(
     *     path="/admin/transactions/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a transaction",
     *     @OA\Parameter(
     *         description="Transaction ID",
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
     * @param int $id
     * @return Response
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(int $id): Response
    {
        $transaction = $this->transactionRepository->find($id);

        $this->authorize('delete', $transaction);

        $this->transactionStorage->destroy($transaction);

        return response()->noContent();
    }
}
