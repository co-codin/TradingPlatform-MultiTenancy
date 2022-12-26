<?php

declare(strict_types=1);

namespace Modules\Transaction\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Transaction\Dto\TransactionsMt5TypeDto;
use Modules\Transaction\Http\Requests\TransactionsMt5TypeCreateRequest;
use Modules\Transaction\Http\Requests\TransactionsMt5TypeUpdateRequest;
use Modules\Transaction\Http\Resources\TransactionsMt5TypeResource;
use Modules\Transaction\Models\TransactionsMt5Type;
use Modules\Transaction\Repositories\TransactionsMt5TypeRepository;
use Modules\Transaction\Services\TransactionsMt5TypeStorage;

final class TransactionsMt5TypeController extends Controller
{
    /**
     * @param TransactionsMt5TypeRepository $transactionsMt5TypeRepository
     * @param TransactionsMt5TypeStorage $transactionsMt5TypeStorage
     */
    public function __construct(
        protected TransactionsMt5TypeRepository $transactionsMt5TypeRepository,
        protected TransactionsMt5TypeStorage $transactionsMt5TypeStorage,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/transaction-mt5-types",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get transaction Mt5 type",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/TransactionsMt5TypeCollection")
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
        $this->authorize('viewAny', TransactionsMt5Type::class);

        $transactionsMt5Type = $this->transactionsMt5TypeRepository->jsonPaginate();

        return TransactionsMt5TypeResource::collection($transactionsMt5Type);
    }

    /**
     * @OA\Get(
     *     path="/admin/transaction-mt5-types/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Get transaction Mt5 type data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Transaction Mt5 type ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/TransactionsMt5TypeResource")
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
        $transactionsMt5Type = $this->transactionsMt5TypeRepository->find($id);

        $this->authorize('view', $transactionsMt5Type);

        return new TransactionsMt5TypeResource($transactionsMt5Type);
    }

    /**
     * @OA\Post(
     *     path="/admin/transaction-mt5-types",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new transaction Mt5 type",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "title",
     *                     "mt5_id",
     *                 },
     *                 @OA\Property(property="name", description="Transaction Mt5 type name"),
     *                 @OA\Property(property="title", type="string", description="Transaction Mt5 type title"),
     *                 @OA\Property(property="mt5_id", type="string", description="Transaction Mt5 type mt5_id"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionsMt5TypeResource")
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
    public function store(TransactionsMt5TypeCreateRequest $request): JsonResource
    {
        $this->authorize('create', TransactionsMt5Type::class);

        $transactionsMt5Type = $this->transactionsMt5TypeStorage->store(TransactionsMt5TypeDto::fromFormRequest($request));

        return new TransactionsMt5TypeResource($transactionsMt5Type);
    }

    /**
     * @OA\Put(
     *     path="/admin/transaction-mt5-types/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a transaction Mt5 type",
     *     @OA\Parameter(
     *         description="Transaction Mt5 type ID",
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
     *                 },
     *                 @OA\Property(property="name", description="Transaction Mt5 type name"),
     *                 @OA\Property(property="title", type="string", description="Transaction Mt5 type title"),
     *                 @OA\Property(property="mt5_id", type="string", description="Transaction Mt5 type mt5_id"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionsMt5TypeResource")
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
     *     path="/admin/transaction-mt5-types/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a transaction Mt5 type",
     *     @OA\Parameter(
     *         description="Transaction Mt5 type ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", description="Transaction Mt5 type name"),
     *                 @OA\Property(property="title", type="string", description="Transaction Mt5 type title"),
     *                 @OA\Property(property="mt5_id", type="string", description="Transaction Mt5 type mt5_id"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/TransactionsMt5TypeResource")
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
    public function update(TransactionsMt5TypeUpdateRequest $request, int $id): JsonResource
    {
        $transactionsMt5Type = $this->transactionsMt5TypeRepository->find($id);

        $this->authorize('update', $transactionsMt5Type);

        $transactionsMt5Type = $this->transactionsMt5TypeStorage->update($transactionsMt5Type, TransactionsMt5TypeDto::fromFormRequest($request));

        return new TransactionsMt5TypeResource($transactionsMt5Type);
    }

    /**
     * @OA\Delete(
     *     path="/admin/transaction-mt5-types/{id}",
     *     tags={"Transaction"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a transaction Mt5 type",
     *     @OA\Parameter(
     *         description="Transaction Mt5 type ID",
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
        $transactionsMt5Type = $this->transactionsMt5TypeRepository->find($id);

        $this->authorize('delete', $transactionsMt5Type);

        $this->transactionsMt5TypeStorage->destroy($transactionsMt5Type);

        return response()->noContent();
    }
}
