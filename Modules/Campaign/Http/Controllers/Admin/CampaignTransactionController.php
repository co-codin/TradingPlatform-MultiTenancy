<?php

declare(strict_types=1);

namespace Modules\Campaign\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Campaign\Dto\CampaignTransactionDto;
use Modules\Campaign\Http\Requests\CampaignTransactionCreateRequest;
use Modules\Campaign\Http\Requests\CampaignTransactionUpdateRequest;
use Modules\Campaign\Http\Resources\CampaignTransactionResource;
use Modules\Campaign\Models\CampaignTransaction;
use Modules\Campaign\Repositories\CampaignTransactionRepository;
use Modules\Campaign\Services\CampaignTransactionStorage;
use OpenApi\Annotations as OA;

class CampaignTransactionController extends Controller
{
    /**
     * @param  CampaignTransactionRepository  $repository
     * @param  CampaignTransactionStorage  $storage
     */
    public function __construct(
        protected CampaignTransactionRepository $repository,
        protected CampaignTransactionStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/campaign-transaction",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Get campaigns",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/CampaignTransactionCollection")
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
        $this->authorize('viewAny', CampaignTransaction::class);

        return CampaignTransactionResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Get(
     *     path="/admin/campaign-transaction/{id}",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Get campaign data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Campaign transaction ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/CampaignTransactionResource")
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
        $campaignTransaction = $this->repository->find($id);

        $this->authorize('view', $campaignTransaction);

        return new CampaignTransactionResource($campaignTransaction);
    }

    /**
     * @OA\Post(
     *     path="/admin/campaign-transaction",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new campaign transaction",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "affiliate_id",
     *                     "type",
     *                     "amount",
     *                     "customer_ids",
     *                 },
     *                 @OA\Property(property="id", type="integer", description="Campaign transaction ID"),
     *                 @OA\Property(property="affiliate_id", type="integer", description="Campaign transaction affiliate id"),
     *                 @OA\Property(property="type", type="integer", description="Campaign transaction type (1-Correction, 2-Payment)", enum={1,2}),
     *                 @OA\Property(property="amount", type="float", description="Campaign transaction amount"),
     *                 @OA\Property(property="customer_ids", type="string", description="Campaign transaction customer_ids", example={1, 2, 3}),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/CampaignTransactionResource")
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
    public function store(CampaignTransactionCreateRequest $request): JsonResource
    {
        $this->authorize('create', CampaignTransaction::class);

        return new CampaignTransactionResource($this->storage->store(CampaignTransactionDto::fromFormRequest($request)));
    }

    /**
     * @OA\Put(
     *     path="/admin/campaign-transaction/{id}",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a campaign transaction",
     *     @OA\Parameter(
     *         description="Campaign transaction ID",
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
     *                     "type",
     *                     "amount",
     *                     "customer_ids",
     *                 },
     *                 @OA\Property(property="id", type="integer", description="Campaign transaction ID"),
     *                 @OA\Property(property="type", type="integer", description="Campaign transaction type (1-Correction, 2-Payment)", enum={1,2}),
     *                 @OA\Property(property="amount", type="float", description="Campaign transaction amount"),
     *                 @OA\Property(property="customer_ids", type="string", description="Campaign transaction customer_ids", example={1, 2, 3}),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/CampaignTransactionResource")
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
     *     path="/admin/campaign-transaction/{id}",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a campaign",
     *     @OA\Parameter(
     *         description="Campaign transaction ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="id", type="integer", description="Campaign transaction ID"),
     *                 @OA\Property(property="type", type="integer", description="Campaign transaction type (1-Correction, 2-Payment)", enum={1,2}),
     *                 @OA\Property(property="amount", type="float", description="Campaign transaction amount"),
     *                 @OA\Property(property="customer_ids", type="string", description="Campaign transaction customer_ids", example={1, 2, 3}),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/CampaignTransactionResource")
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
    public function update(CampaignTransactionUpdateRequest $request, int $id): JsonResource
    {
        $campaign = $this->repository->find($id);

        $this->authorize('update', $campaign);

        return new CampaignTransactionResource($this->storage->update($campaign, CampaignTransactionDto::fromFormRequest($request)));
    }
}
