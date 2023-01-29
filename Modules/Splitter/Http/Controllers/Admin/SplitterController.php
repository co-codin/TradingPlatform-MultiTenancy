<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Splitter\Dto\SplitterDto;
use Modules\Splitter\Http\Requests\SplitterCreateRequest;
use Modules\Splitter\Http\Requests\SplitterPositionsUpdateRequest;
use Modules\Splitter\Http\Requests\SplitterUpdateRequest;
use Modules\Splitter\Http\Resources\SplitterResource;
use Modules\Splitter\Models\Splitter;
use Modules\Splitter\Repositories\SplitterRepository;
use Modules\Splitter\Services\SplitterStorage;
use OpenApi\Annotations as OA;

final class SplitterController extends Controller
{
    /**
     * @param  SplitterRepository  $repository
     * @param  SplitterStorage  $storage
     */
    public function __construct(
        protected SplitterRepository $repository,
        protected SplitterStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/splitter",
     *     tags={"Splitter"},
     *     security={ {"sanctum": {} }},
     *     summary="Get splitters",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/SplitterCollection")
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
     * @param  Request  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(Request $request): JsonResource
    {
        $this->authorize('viewAny', Splitter::class);

        return SplitterResource::collection(
            $this->repository->whereUserId($request->user()->id)->jsonPaginate()
        );
    }

    /**
     * @OA\Get(
     *     path="/admin/splitter/{id}",
     *     tags={"Splitter"},
     *     security={ {"sanctum": {} }},
     *     summary="Get splitter data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Splitter ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/SplitterResource")
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
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(Request $request, int $id): JsonResource
    {
        $splitter = $this->repository->whereUserId($request->user()->id)->find($id);

        $this->authorize('view', $splitter);

        return new SplitterResource($splitter);
    }

    /**
     * @OA\Post(
     *     path="/admin/splitter",
     *     tags={"Splitter"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new splitter",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "is_active",
     *                     "conditions",
     *                     "share_conditions",
     *                     "splitter_choice",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Splitter name"),
     *                 @OA\Property(property="is_active", type="boolean", description="Splitter is active"),
     *                 @OA\Property(
     *                     property="conditions",
     *                     type="array",
     *                     description="Splitter conditions",
     *                     @OA\Items(
     *                         type="object",
     *                         required={
     *                             "field",
     *                             "operator",
     *                             "value",
     *                         },
     *                         @OA\Property(property="field", type="string", example="Gender", description="Splitter condition field"),
     *                         @OA\Property(property="operator", type="string", example="=", description="Splitter condition field operator"),
     *                         @OA\Property(property="value", type="string", example="male", description="Splitter condition field value"),
     *                     )
     *                 ),
     *                 @OA\Property(property="share_conditions", type="string", description="Splitter share conditions", example={}),
     *                 @OA\Property(
     *                     property="splitter_choice",
     *                     description="Array of splitter_choice",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         required={
     *                             "type",
     *                             "option_per_day",
     *                         },
     *                         @OA\Property(property="type", type="integer", description="Splitter Choice type (Desk = 1, Worker = 2)", example="1"),
     *                         @OA\Property(property="option_per_day", type="integer", description="Splitter Choice option per day (Percent Per Day = 1 , Cap Per Day = 2)", example="1"),
     *                     ),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/SplitterResource")
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
    public function store(SplitterCreateRequest $request): JsonResource
    {
        $this->authorize('create', Splitter::class);

        return new SplitterResource($this->storage->store($request->user(), SplitterDto::fromFormRequest($request)));
    }

    /**
     * @OA\Put(
     *     path="/admin/splitter/{id}",
     *     tags={"Splitter"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a splitter",
     *     @OA\Parameter(
     *         description="Splitter ID",
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
     *                     "is_active",
     *                     "conditions",
     *                     "share_conditions",
     *                     "splitter_choice",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Splitter name"),
     *                 @OA\Property(property="is_active", type="boolean", description="Splitter is active"),
     *                 @OA\Property(
     *                     property="conditions",
     *                     type="array",
     *                     description="Splitter conditions",
     *                     @OA\Items(
     *                         type="object",
     *                         required={
     *                             "field",
     *                             "operator",
     *                             "value",
     *                         },
     *                         @OA\Property(property="field", type="string", example="Gender", description="Splitter condition field"),
     *                         @OA\Property(property="operator", type="string", example="=", description="Splitter condition field operator"),
     *                         @OA\Property(property="value", type="string", example="male", description="Splitter condition field value"),
     *                     )
     *                 ),
     *                 @OA\Property(property="share_conditions", type="string", description="Splitter share conditions", example={}),
     *                 @OA\Property(
     *                     property="splitter_choice",
     *                     description="Array of splitter_choice",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         required={
     *                             "type",
     *                             "option_per_day",
     *                         },
     *                         @OA\Property(property="type", type="integer", description="Splitter Choice type (Desk = 1, Worker = 2)", example="1"),
     *                         @OA\Property(property="option_per_day", type="integer", description="Splitter Choice option per day (Percent Per Day = 1 , Cap Per Day = 2)", example="1"),
     *                     ),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/SplitterResource")
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
     *     path="/admin/splitter/{id}",
     *     tags={"Splitter"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a splitter",
     *     @OA\Parameter(
     *         description="Splitter ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Splitter name"),
     *                 @OA\Property(property="is_active", type="boolean", description="Splitter is active"),
     *                 @OA\Property(
     *                     property="conditions",
     *                     type="array",
     *                     description="Splitter conditions",
     *                     @OA\Items(
     *                         type="object",
     *                         required={
     *                             "field",
     *                             "operator",
     *                             "value",
     *                         },
     *                         @OA\Property(property="field", type="string", example="Gender", description="Splitter condition field"),
     *                         @OA\Property(property="operator", type="string", example="=", description="Splitter condition field operator"),
     *                         @OA\Property(property="value", type="string", example="male", description="Splitter condition field value"),
     *                     )
     *                 ),
     *                 @OA\Property(property="share_conditions", type="string", description="Splitter share conditions", example={}),
     *                 @OA\Property(
     *                     property="splitter_choice",
     *                     description="Array of splitter_choice",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         required={
     *                             "type",
     *                             "option_per_day",
     *                         },
     *                         @OA\Property(property="type", type="integer", description="Splitter Choice type (Desk = 1, Worker = 2)", example="1"),
     *                         @OA\Property(property="option_per_day", type="integer", description="Splitter Choice option per day (Percent Per Day = 1 , Cap Per Day = 2)", example="1"),
     *                     ),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/SplitterResource")
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
    public function update(SplitterUpdateRequest $request, int $id): JsonResource
    {
        $splitter = $this->repository->whereUserId($request->user()->id)->findOrFail($id);

        $this->authorize('update', $splitter);

        return new SplitterResource($this->storage->update($splitter, SplitterDto::fromFormRequest($request)));
    }

    /**
     * @OA\Delete(
     *     path="/admin/splitter/{id}",
     *     tags={"Splitter"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a splitter",
     *     @OA\Parameter(
     *         description="Splitter ID",
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
    public function destroy(Request $request, int $id): Response
    {
        $splitter = $this->repository->whereUserId($request->user()->id)->findOrFail($id);

        $this->authorize('delete', $splitter);

        $this->storage->delete($splitter);

        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/admin/splitter/update-positions",
     *     tags={"Splitter"},
     *     security={ {"sanctum": {} }},
     *     summary="Update splitter positions",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "splitterids",
     *                 },
     *                 @OA\Property(property="splitterids", type="array", @OA\Items(type="integer"), description="Splitter IDs", example="[1, 2, 3]"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content"
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
    public function updatePositions(SplitterPositionsUpdateRequest $request): Response
    {
        $this->authorize('updatePositions', Splitter::class);

        $this->storage->updatePositions($request->user(), $request->validated('splitterids'));

        return response()->noContent();
    }
}
