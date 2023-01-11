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

class CampaignController extends Controller
{

    /**
     * @param CampaignRepository $repository
     * @param CampaignStorage $storage
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
     *                     "cpa",
     *                     "working_hours",
     *                     "daily_cap",
     *                     "crg",
     *                 },
     *                 @OA\Property(property="cpa", type="float", description="Campaign cpa"),
     *                 @OA\Property(property="working_hours", type="array", @OA\Items(type="string"), description="Campaign working hours by week days"),
     *                 @OA\Property(property="daily_cap", type="integer", description="Campaign daily cap"),
     *                 @OA\Property(property="crg", type="float", description="Campaign crg"),
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
     *                     "cpa",
     *                     "working_hours",
     *                     "daily_cap",
     *                     "crg",
     *                 },
     *                 @OA\Property(property="cpa", type="float", description="Campaign cpa"),
     *                 @OA\Property(property="working_hours", type="array", @OA\Items(type="string"), description="Campaign working hours by week days"),
     *                 @OA\Property(property="daily_cap", type="integer", description="Campaign daily cap"),
     *                 @OA\Property(property="crg", type="float", description="Campaign crg"),
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
     *                 @OA\Property(property="cpa", type="float", description="Campaign cpa"),
     *                 @OA\Property(property="working_hours", type="array", @OA\Items(type="string"), description="Campaign working hours by week days"),
     *                 @OA\Property(property="daily_cap", type="integer", description="Campaign daily cap"),
     *                 @OA\Property(property="crg", type="float", description="Campaign crg"),
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
     * @OA\Delete(
     *     path="/admin/campaign/{id}",
     *     tags={"Campaign"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a campaign",
     *     @OA\Parameter(
     *         description="Campaign ID",
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
        $campaign = $this->repository->find($id);

        $this->authorize('delete', $campaign);

        $this->storage->destroy($campaign);

        return response()->noContent();
    }
}
