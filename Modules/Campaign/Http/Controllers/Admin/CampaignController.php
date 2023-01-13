<?php

declare(strict_types=1);

namespace Modules\Campaign\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Campaign\Dto\CampaignDto;
use Modules\Campaign\Http\Requests\CampaignCreateRequest;
use Modules\Campaign\Http\Requests\CampaignUpdateRequest;
use Modules\Campaign\Http\Resources\CampaignResource;
use Modules\Campaign\Models\Campaign;
use Modules\Campaign\Repositories\CampaignRepository;
use Modules\Campaign\Services\CampaignStorage;
use OpenApi\Annotations as OA;

class CampaignController extends Controller
{
    /**
     * @param  CampaignRepository  $repository
     * @param  CampaignStorage  $storage
     */
    public function __construct(
        protected CampaignRepository $repository,
        protected CampaignStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/campaign",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Get campaigns",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/CampaignCollection")
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
        $this->authorize('viewAny', Campaign::class);

        return CampaignResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Get(
     *     path="/admin/campaign/{id}",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Get campaign data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Campaign ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/CampaignResource")
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
        $campaign = $this->repository->find($id);

        $this->authorize('view', $campaign);

        return new CampaignResource($campaign);
    }

    /**
     * @OA\Post(
     *     path="/admin/campaign",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new campaign",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "cpa",
     *                     "working_hours",
     *                     "daily_cap",
     *                     "crg",
     *                     "is_active",
     *                     "balance",
     *                     "monthly_cr",
     *                     "monthly_pv",
     *                     "crg_cost",
     *                     "ftd_cost",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Campaign name"),
     *                 @OA\Property(property="cpa", type="float", description="Campaign cpa"),
     *                 @OA\Property(property="working_hours", type="string", description="Campaign working hours by week days", example={"1":{"start":"10:00","end":"18:00"},"2":{"start":"10:00","end":"18:00"},"3":{"start":"10:00","end":"18:00"},"4":{"start":"10:00","end":"18:00"},"5":{"start":"10:00","end":"18:00"}}),
     *                 @OA\Property(property="daily_cap", type="integer", description="Campaign daily cap"),
     *                 @OA\Property(property="crg", type="float", description="Campaign crg"),
     *                 @OA\Property(property="is_active", type="boolean", description="Campaign is active"),
     *                 @OA\Property(property="balance", type="float", description="Campaign balance"),
     *                 @OA\Property(property="monthly_cr", type="integer", description="Campaign monthly cr"),
     *                 @OA\Property(property="monthly_pv", type="integer", description="Campaign monthly pv"),
     *                 @OA\Property(property="crg_cost", type="float", description="Campaign crg cost"),
     *                 @OA\Property(property="ftd_cost", type="float", description="Campaign ftd cost"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/CampaignResource")
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
    public function store(CampaignCreateRequest $request): JsonResource
    {
        $this->authorize('create', Campaign::class);

        return new CampaignResource($this->storage->store(CampaignDto::fromFormRequest($request)));
    }

    /**
     * @OA\Put(
     *     path="/admin/campaign/{id}",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a campaign",
     *     @OA\Parameter(
     *         description="Campaign ID",
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
     *                     "cpa",
     *                     "working_hours",
     *                     "daily_cap",
     *                     "crg",
     *                     "is_active",
     *                     "balance",
     *                     "monthly_cr",
     *                     "monthly_pv",
     *                     "crg_cost",
     *                     "ftd_cost",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Campaign name"),
     *                 @OA\Property(property="cpa", type="float", description="Campaign cpa"),
     *                 @OA\Property(property="working_hours", type="string", description="Campaign working hours by week days", example={"1":{"start":"10:00","end":"18:00"},"2":{"start":"10:00","end":"18:00"},"3":{"start":"10:00","end":"18:00"},"4":{"start":"10:00","end":"18:00"},"5":{"start":"10:00","end":"18:00"}}),
     *                 @OA\Property(property="daily_cap", type="integer", description="Campaign daily cap"),
     *                 @OA\Property(property="crg", type="float", description="Campaign crg"),
     *                 @OA\Property(property="is_active", type="boolean", description="Campaign is active"),
     *                 @OA\Property(property="balance", type="float", description="Campaign balance"),
     *                 @OA\Property(property="monthly_cr", type="integer", description="Campaign monthly cr"),
     *                 @OA\Property(property="monthly_pv", type="integer", description="Campaign monthly pv"),
     *                 @OA\Property(property="crg_cost", type="float", description="Campaign crg cost"),
     *                 @OA\Property(property="ftd_cost", type="float", description="Campaign ftd cost"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/CampaignResource")
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
     *     path="/admin/campaign/{id}",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a campaign",
     *     @OA\Parameter(
     *         description="Campaign ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Campaign name"),
     *                 @OA\Property(property="cpa", type="float", description="Campaign cpa"),
     *                 @OA\Property(property="working_hours", type="string", description="Campaign working hours by week days", example={"1":{"start":"10:00","end":"18:00"},"2":{"start":"10:00","end":"18:00"},"3":{"start":"10:00","end":"18:00"},"4":{"start":"10:00","end":"18:00"},"5":{"start":"10:00","end":"18:00"}}),
     *                 @OA\Property(property="daily_cap", type="integer", description="Campaign daily cap"),
     *                 @OA\Property(property="crg", type="float", description="Campaign crg"),
     *                 @OA\Property(property="is_active", type="boolean", description="Campaign is active"),
     *                 @OA\Property(property="balance", type="float", description="Campaign balance"),
     *                 @OA\Property(property="monthly_cr", type="integer", description="Campaign monthly cr"),
     *                 @OA\Property(property="monthly_pv", type="integer", description="Campaign monthly pv"),
     *                 @OA\Property(property="crg_cost", type="float", description="Campaign crg cost"),
     *                 @OA\Property(property="ftd_cost", type="float", description="Campaign ftd cost"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/CampaignResource")
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
    public function update(CampaignUpdateRequest $request, int $id): JsonResource
    {
        $campaign = $this->repository->find($id);

        $this->authorize('update', $campaign);

        return new CampaignResource($this->storage->update($campaign, CampaignDto::fromFormRequest($request)));
    }

    /**
     * @OA\Patch(
     *     path="/admin/campaign/{id}/change-status",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Change of campaign status",
     *     @OA\Parameter(
     *         description="Campaign ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/CampaignResource")
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
    public function changeStatus(int $id): JsonResource
    {
        $campaign = $this->repository->find($id);

        $this->authorize('update', $campaign);

        return new CampaignResource($this->storage->changeStatus($campaign));
    }
}
