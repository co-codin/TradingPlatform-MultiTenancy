<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Transaction\Dto\WalletDto;
use Modules\Transaction\Http\Requests\WalletCreateRequest;
use Modules\Transaction\Http\Requests\WalletUpdateRequest;
use Modules\Transaction\Http\Resources\WalletResource;
use Modules\Transaction\Models\Wallet;
use Modules\Transaction\Repositories\WalletRepository;
use Modules\Transaction\Services\WalletStorage;

final class WalletController extends Controller
{
    /**
     * @param WalletRepository $repository
     * @param WalletStorage $storage
     */
    public function __construct(
        protected WalletRepository $repository,
        protected WalletStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/transaction-wallets",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get wallet",
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
        $this->authorize('viewAny', Wallet::class);

        $wallet = $this->repository->jsonPaginate();

        return WalletResource::collection($wallet);
    }

    /**
     * @OA\Get(
     *     path="/admin/transaction-wallets/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get wallet data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Wallet ID",
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
        $wallet = $this->repository->find($id);

        $this->authorize('view', $wallet);

        return new WalletResource($wallet);
    }

    /**
     * @OA\Post(
     *     path="/admin/transaction-wallets",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new wallet",
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
     *                 @OA\Property(property="name", description="Wallet name"),
     *                 @OA\Property(property="title", type="string", description="Wallet title"),
     *                 @OA\Property(property="mt5_id", type="string", description="Wallet mt5_id"),
     *                 @OA\Property(property="currency_id", type="integer", description="Wallet currency_id"),
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
    public function store(WalletCreateRequest $request): JsonResource
    {
        $this->authorize('create', Wallet::class);

        $wallet = $this->storage->store(WalletDto::fromFormRequest($request));

        return new WalletResource($wallet);
    }

    /**
     * @OA\Put(
     *     path="/admin/transaction-wallets/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a wallet",
     *     @OA\Parameter(
     *         description="Wallet ID",
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
     *                 @OA\Property(property="name", description="Wallet name"),
     *                 @OA\Property(property="title", type="string", description="Wallet title"),
     *                 @OA\Property(property="mt5_id", type="string", description="Wallet mt5_id"),
     *                 @OA\Property(property="currency_id", type="integer", description="Wallet currency_id"),
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
     *     summary="Update a wallet",
     *     @OA\Parameter(
     *         description="Wallet ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", description="Wallet name"),
     *                 @OA\Property(property="title", type="string", description="Wallet title"),
     *                 @OA\Property(property="mt5_id", type="string", description="Wallet mt5_id"),
     *                 @OA\Property(property="currency_id", type="integer", description="Wallet currency_id"),
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
    public function update(WalletUpdateRequest $request, int $id): JsonResource
    {
        $wallet = $this->repository->find($id);

        $this->authorize('update', $wallet);

        $wallet = $this->storage->update($wallet, WalletDto::fromFormRequest($request));

        return new WalletResource($wallet);
    }

    /**
     * @OA\Delete(
     *     path="/admin/transaction-wallets/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a wallet",
     *     @OA\Parameter(
     *         description="Wallet ID",
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
        $wallet = $this->repository->find($id);

        $this->authorize('delete', $wallet);

        $this->storage->destroy($wallet);

        return response()->noContent();
    }
}
