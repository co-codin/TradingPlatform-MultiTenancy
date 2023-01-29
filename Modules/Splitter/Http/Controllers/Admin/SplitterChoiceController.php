<?php

namespace Modules\Splitter\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Modules\Splitter\Enums\SplitterChoiceType;
use Modules\Splitter\Http\Requests\SplitterChoicePutDeskRequest;
use Modules\Splitter\Http\Requests\SplitterChoicePutUserRequest;
use Modules\Splitter\Http\Resources\SplitterChoiceResource;
use Modules\Splitter\Repositories\SplitterChoiceRepository;
use Modules\Splitter\Services\SplitterStorage;
use OpenApi\Annotations as OA;

class SplitterChoiceController extends Controller
{
    /**
     * @param  SplitterChoiceRepository  $repository
     * @param  SplitterStorage  $storage
     */
    public function __construct(
        protected SplitterChoiceRepository $repository,
        protected SplitterStorage $storage,
    ) {
    }

    /**
     * @OA\Put(
     *     path="/admin/splitter/splitter-choice/{id}/desk",
     *     tags={"Splitter"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a splitter choice desks",
     *     @OA\Parameter(
     *         description="Splitter choice ID",
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
     *                     "ids",
     *                 },
     *                 @OA\Property(property="ids", type="array", @OA\Items(type="integer"), description="Desks IDs"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/SplitterChoiceResource")
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
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function desk(SplitterChoicePutDeskRequest $request, int $id)
    {
        $splitterChoice = $this->repository->whereRelation('splitter', 'user_id', $request->user()->id)
            ->whereType(SplitterChoiceType::DESK)
            ->with(['splitter', 'desks'])
            ->findOrFail($id);

        $this->authorize('update', $splitterChoice->splitter()->first());

        return new SplitterChoiceResource($this->storage->put($splitterChoice, $request->validated('ids')));
    }

    /**
     * @OA\Put(
     *     path="/admin/splitter/splitter-choice/{id}/worker",
     *     tags={"Splitter"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a splitter choice workers",
     *     @OA\Parameter(
     *         description="Splitter choice ID",
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
     *                     "ids",
     *                 },
     *                 @OA\Property(property="ids", type="array", @OA\Items(type="integer"), description="Workers IDs"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/SplitterChoiceResource")
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
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function worker(SplitterChoicePutUserRequest $request, int $id)
    {
        $splitterChoice = $this->repository->whereRelation('splitter', 'user_id', $request->user()->id)
            ->whereType(SplitterChoiceType::WORKER)
            ->with(['splitter', 'workers'])
            ->findOrFail($id);

        $this->authorize('update', $splitterChoice->splitter()->first());

        return new SplitterChoiceResource($this->storage->put($splitterChoice, $request->validated('ids')));
    }
}
