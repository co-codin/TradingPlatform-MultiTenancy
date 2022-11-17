<?php

namespace Modules\User\Http\Controllers\Admin\DisplayOption;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\User\Http\Requests\DisplayOption\UserDisplayOptionCreateRequest;
use Modules\User\Http\Requests\DisplayOption\UserDisplayOptionUpdateRequest;
use Modules\User\Http\Resources\DisplayOptionResource;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserDisplayOptionStorage;
use Modules\User\Services\UserStorage;

class UserDisplayOptionController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
        protected UserStorage $userStorage
    ) {}

    /**
     * @OA\Post(
     *     path="/admin/workers/{workerId}/display-options",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new worker`s display option",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "columns"
     *                 },
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
     * @param UserDisplayOptionCreateRequest $request
     * @param int $user
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function store(UserDisplayOptionCreateRequest $request, int $user): JsonResource
    {dd('asedasd');
        $user = $this->userRepository->find($user);

        $this->authorize('create', $user);

        return new DisplayOptionResource(
            $this->userStorage->storeDisplayOption($user, $request->validated())
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
     *                     "name",
     *                 },
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
     * @param UserDisplayOptionUpdateRequest $request
     * @param int $user
     * @param int $displayOption
     * @return JsonResource
     * @throws AuthorizationException
     * @throws Exception
     */
    public function update(UserDisplayOptionUpdateRequest $request, int $user, int $displayOption): JsonResource
    {
        $user = $this->userRepository->find($user);

        $this->authorize('update', [$user, $displayOption]);

        return new DisplayOptionResource(
            $this->userStorage->updateDisplayOption($user, $displayOption, $request->validated())
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
     * @param int $user
     * @param int $displayOption
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(int $user, int $displayOption): Response
    {
        $user = $this->userRepository->find($user);

        $this->authorize('delete', [$user, $displayOption]);

        $this->userStorage->destroyDisplayOption($user, $displayOption);

        return response()->noContent();
    }
}
