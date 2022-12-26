<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Transaction\Dto\TransactionsWalletDto;
use Modules\Transaction\Http\Requests\TransactionsWalletCreateRequest;
use Modules\Transaction\Http\Requests\TransactionsWalletUpdateRequest;
use Modules\Transaction\Http\Resources\TransactionsWalletResource;
use Modules\Transaction\Models\TransactionsWallet;
use Modules\Transaction\Repositories\TransactionsWalletRepository;
use Modules\Transaction\Services\TransactionsWalletStorage;

final class TransactionsWalletController extends Controller
{
    /**
     * @param TransactionsWalletRepository $transactionsWalletRepository
     * @param TransactionsWalletStorage $transactionsWalletStorage
     */
    public function __construct(
        protected TransactionsWalletRepository $transactionsWalletRepository,
        protected TransactionsWalletStorage $transactionsWalletStorage,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/transaction-wallets",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get transaction wallet",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/TransactionsWalletCollection")
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
        $this->authorize('viewAny', TransactionsWallet::class);

        $transactionsWallet = $this->transactionsWalletRepository->jsonPaginate();

        return TransactionsWalletResource::collection($transactionsWallet);
    }

    /**
     * @OA\Get(
     *     path="/admin/transaction-wallets/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get transaction wallet data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Transaction wallet ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/TransactionsWalletResource")
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
        $transactionsWallet = $this->transactionsWalletRepository->find($id);

        $this->authorize('view', $transactionsWallet);

        return new TransactionsWalletResource($transactionsWallet);
    }

    /**
     * @OA\Post(
     *     path="/admin/transaction-wallets",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new transaction wallet",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "title",
     *                     "mt5_id",
     *                     "currency_id",
     *                 },
     *                 @OA\Property(property="name", description="Transaction wallet name"),
     *                 @OA\Property(property="title", type="string", description="Transaction wallet title"),
     *                 @OA\Property(property="mt5_id", type="string", description="Transaction wallet mt5_id"),
     *                 @OA\Property(property="currency_id", type="integer", description="Transaction wallet currency_id"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionsWalletResource")
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
    public function store(TransactionsWalletCreateRequest $request): JsonResource
    {
        $this->authorize('create', TransactionsWallet::class);

        $transactionsWallet = $this->transactionsWalletStorage->store(TransactionsWalletDto::fromFormRequest($request));

        return new TransactionsWalletResource($transactionsWallet);
    }

    /**
     * @OA\Put(
     *     path="/admin/transaction-wallets/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a transaction wallet",
     *     @OA\Parameter(
     *         description="Transaction wallet ID",
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
     *                     "mt5_id",
     *                     "currency_id",
     *                 },
     *                 @OA\Property(property="name", description="Transaction wallet name"),
     *                 @OA\Property(property="title", type="string", description="Transaction wallet title"),
     *                 @OA\Property(property="mt5_id", type="string", description="Transaction wallet mt5_id"),
     *                 @OA\Property(property="currency_id", type="integer", description="Transaction wallet currency_id"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionsWalletResource")
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
     *     path="/admin/transaction-wallets/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a transaction wallet",
     *     @OA\Parameter(
     *         description="Transaction wallet ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", description="Transaction wallet name"),
     *                 @OA\Property(property="title", type="string", description="Transaction wallet title"),
     *                 @OA\Property(property="mt5_id", type="string", description="Transaction wallet mt5_id"),
     *                 @OA\Property(property="currency_id", type="integer", description="Transaction wallet currency_id"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionsWalletResource")
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
    public function update(TransactionsWalletUpdateRequest $request, int $id): JsonResource
    {
        $transactionsWallet = $this->transactionsWalletRepository->find($id);

        $this->authorize('update', $transactionsWallet);

        $transactionsWallet = $this->transactionsWalletStorage->update($transactionsWallet, TransactionsWalletDto::fromFormRequest($request));

        return new TransactionsWalletResource($transactionsWallet);
    }

    /**
     * @OA\Delete(
     *     path="/admin/transaction-wallets/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a transaction wallet",
     *     @OA\Parameter(
     *         description="Transaction wallet ID",
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
        $transactionsWallet = $this->transactionsWalletRepository->find($id);

        $this->authorize('delete', $transactionsWallet);

        $this->transactionsWalletStorage->destroy($transactionsWallet);

        return response()->noContent();
    }
}
