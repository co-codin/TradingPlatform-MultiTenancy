<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\DisplayOption;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\User\Http\Requests\DisplayOption\UserDisplayOptionCreateRequest;
use Modules\User\Http\Requests\DisplayOption\UserDisplayOptionUpdateRequest;
use Modules\User\Http\Resources\DisplayOptionResource;
use Modules\User\Models\DisplayOption;
use Modules\User\Repositories\DisplayOptionRepository;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserDisplayOptionStorage;

final class UserDisplayOptionController extends Controller
{
    /**
     * @param  UserRepository  $userRepository
     * @param  DisplayOptionRepository  $displayOptionRepository
     * @param  UserDisplayOptionStorage  $userDisplayOptionStorage
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected DisplayOptionRepository $displayOptionRepository,
        protected UserDisplayOptionStorage $userDisplayOptionStorage
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/workers/{workerId}/display-options",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Show display options list.",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="workerId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/WorkerCollection")
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
     *
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', DisplayOption::class);

        $displayOptions = $this->displayOptionRepository->jsonPaginate();

        return DisplayOptionResource::collection($displayOptions);
    }

    /**
     * @OA\Get(
     *     path="/admin/workers/{workerId}/display-options/{displayOptionId}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Show display option.",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="workerId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Display option id",
     *         in="path",
     *         name="displayOptionId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/DisplayOptionResource")
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
     * Show user`s display option.
     *
     * @param  int  $user
     * @param  int  $displayOption
     * @return DisplayOptionResource
     *
     * @throws AuthorizationException
     */
    public function show(int $user, int $displayOption): JsonResource
    {
        $displayOption = $this->displayOptionRepository->find($displayOption);

        $this->authorize('view', $displayOption);

        return new DisplayOptionResource($displayOption);
    }

    /**
     * @OA\Post(
     *     path="/admin/workers/{workerId}/display-options",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new worker`s display option",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="workerId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "model_id",
     *                     "name",
     *                     "columns"
     *                 },
     *                 @OA\Property(property="model_id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="columns", type="array", @OA\Items(type="string")),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/DisplayOptionResource")
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
     * Store new user`s display option.
     *
     * @param  UserDisplayOptionCreateRequest  $request
     * @param  int  $user
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function store(UserDisplayOptionCreateRequest $request, int $user): JsonResource
    {
        $user = $this->userRepository->find($user);

        $this->authorize('create', DisplayOption::class);

        return new DisplayOptionResource(
            $this->userDisplayOptionStorage->store($user, $request->validated())
        );
    }

    /**
     * @OA\Put(
     *     path="/admin/workers/{workerId}/display-options/{displayOptionId}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a worker`s display option",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="workerId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Display option id",
     *         in="path",
     *         name="displayOptionId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "model_id",
     *                     "name",
     *                 },
     *                 @OA\Property(property="model_id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="columns", type="array", @OA\Items(type="string")),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/DisplayOptionResource")
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
     *     path="/admin/workers/{workerId}/display-options/{displayOptionId}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a worker",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="workerId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Display option id",
     *         in="path",
     *         name="displayOptionId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="model_id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="columns", type="array", @OA\Items(type="string")),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/DisplayOptionResource")
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
     * Update user`s display option.
     *
     * @param  UserDisplayOptionUpdateRequest  $request
     * @param  int  $user
     * @param  int  $displayOption
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function update(UserDisplayOptionUpdateRequest $request, int $user, int $displayOption): JsonResource
    {
        $user = $this->userRepository->find($user);
        $displayOption = $this->displayOptionRepository->find($displayOption);

        $this->authorize('update', $displayOption);

        return new DisplayOptionResource(
            $this->userDisplayOptionStorage->update($user, $displayOption, $request->validated())
        );
    }

    /**
     * @OA\Delete(
     *     path="/admin/workers/{workerId}/display-options/{displayOptionId}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a worker`s display option",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="workerId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Display option id",
     *         in="path",
     *         name="displayOptionId",
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
     * Delete user`s display option.
     *
     * @param  int  $user
     * @param  int  $displayOption
     * @return Response
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(int $user, int $displayOption): Response
    {
        $user = $this->userRepository->find($user);
        $displayOption = $this->displayOptionRepository->find($displayOption);

        $this->authorize('delete', $displayOption);

        $this->userDisplayOptionStorage->destroy($user, $displayOption);

        return response()->noContent();
    }
}
