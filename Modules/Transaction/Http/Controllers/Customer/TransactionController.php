<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Transaction\Dto\TransactionDto;
use Modules\Transaction\Http\Requests\Customer\TransactionCreateRequest;
use Modules\Transaction\Http\Resources\TransactionResource;
use Modules\Transaction\Repositories\TransactionRepository;
use Modules\Transaction\Services\Customer\TransactionStorage;

final class TransactionController extends Controller
{
    /**
     * @param  TransactionStorage  $transactionStorage
     * @param  TransactionRepository  $transactionRepository
     */
    public function __construct(
        protected TransactionStorage $transactionStorage,
        protected TransactionRepository $transactionRepository,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/customer/transactions",
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
     *     path="/customer/transactions/{id}",
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
     *     path="/customer/transactions",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new transaction",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "type",
     *                     "mt5_type",
     *                     "status",
     *                     "amount",
     *                     "customer_id",
     *                     "method_id",
     *                     "wallet_id"
     *                 },
     *                 @OA\Property(property="type", type="string", enum={"withdrawal", "deposit"}, description="Transaction type"),
     *                 @OA\Property(property="mt5_type", type="string", enum={"balance", "credit", "charge", "correction", "bonus"}, description="Transaction MT5 type"),
     *                 @OA\Property(property="status", type="string", enum={"approved", "declined", "canceled", "pending"}, description="Transaction status"),
     *                 @OA\Property(property="amount", type="number", format="float", description="ID of customer"),
     *                 @OA\Property(property="customer_id", type="integer", description="ID of customer"),
     *                 @OA\Property(property="method_id", type="integer", description="ID of method"),
     *                 @OA\Property(property="wallet_id", type="integer", description="ID of wallet"),
     *                 @OA\Property(property="external_id", type="string", description="ID of transaction in external service"),
     *                 @OA\Property(property="description", type="string", description="Transaction description"),
     *                 @OA\Property(property="is_test", type="boolean", description="Test transaction flag"),
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
}
