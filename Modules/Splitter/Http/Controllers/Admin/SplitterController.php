<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Splitter\Dto\SplitterDto;
use Modules\Splitter\Http\Requests\SplitterCreateRequest;
use Modules\Splitter\Http\Requests\SplitterUpdateRequest;
use Modules\Splitter\Http\Resources\SplitterResource;
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
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Splitter::class);

        return SplitterResource::collection(
            $this->repository
                ->orderBy('is_active', 'DESC')
                ->orderBy('position', 'ASC')
                ->jsonPaginate()
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
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $splitter = $this->repository->find($id);

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
     *                     "user_id",
     *                     "name",
     *                     "is_active",
     *                     "conditions",
     *                     "share_conditions",
     *                     "position",
     *                 },
     *                 @OA\Property(property="user_id", type="integer", description="Splitter user id"),
     *                 @OA\Property(property="name", type="string", description="Splitter name"),
     *                 @OA\Property(property="is_active", type="boolean", description="Splitter is active"),
     *                 @OA\Property(property="conditions", type="string", description="Splitter conditions", example={}),
     *                 @OA\Property(property="share_conditions", type="string", description="Splitter share conditions", example={}),
     *                 @OA\Property(property="position", type="integer", description="Splitter position"),
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

        return new SplitterResource($this->storage->store(SplitterDto::fromFormRequest($request)));
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
     *                     "position",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Splitter name"),
     *                 @OA\Property(property="is_active", type="boolean", description="Splitter is active"),
     *                 @OA\Property(property="conditions", type="string", description="Splitter conditions", example={}),
     *                 @OA\Property(property="share_conditions", type="string", description="Splitter share conditions", example={}),
     *                 @OA\Property(property="position", type="integer", description="Splitter position"),
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
     *                 @OA\Property(property="conditions", type="string", description="Splitter conditions", example={}),
     *                 @OA\Property(property="share_conditions", type="string", description="Splitter share conditions", example={}),
     *                 @OA\Property(property="position", type="integer", description="Splitter position"),
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
        $splitter = $this->repository->find($id);

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
    public function destroy(int $id): Response
    {
        $splitter = $this->repository->find($id);

        $this->authorize('delete', $splitter);

        $this->storage->delete($splitter);

        return response()->noContent();
    }
}
