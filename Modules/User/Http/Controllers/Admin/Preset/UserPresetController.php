<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Admin\Preset;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\User\Http\Requests\Preset\UserPresetCreateRequest;
use Modules\User\Http\Requests\Preset\UserPresetUpdateRequest;
use Modules\User\Http\Resources\PresetResource;
use Modules\User\Models\Preset;
use Modules\User\Repositories\PresetRepository;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserPresetStorage;

final class UserPresetController extends Controller
{
    /**
     * @param  UserRepository  $userRepository
     * @param  PresetRepository  $presetRepository
     * @param  UserPresetStorage  $userPresetStorage
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected PresetRepository $presetRepository,
        protected UserPresetStorage $userPresetStorage
    ) {
    }

    /**
     * @OA\Get(
     *     path="/admin/workers/{workerId}/presets/{presetId}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Show preset.",
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
     *         name="presetId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/PresetResource")
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
     * Show user`s preset.
     *
     * @param  int  $user
     * @param  int  $preset
     * @return PresetResource
     *
     * @throws AuthorizationException
     */
    public function show(int $user, int $preset): JsonResource
    {
        $preset = $this->presetRepository->find($preset);

        $this->authorize('view', $preset);

        return new PresetResource($preset);
    }

    /**
     * @OA\Post(
     *     path="/admin/workers/{workerId}/presets",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new worker`s preset",
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
     *         @OA\JsonContent(ref="#/components/schemas/PresetResource")
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
     * Store new user`s preset.
     *
     * @param  UserPresetCreateRequest  $request
     * @param  int  $user
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function store(UserPresetCreateRequest $request, int $user): JsonResource
    {
        $user = $this->userRepository->find($user);

        $this->authorize('create', Preset::class);

        return new PresetResource(
            $this->userPresetStorage->store($user, $request->validated())
        );
    }

    /**
     * @OA\Put(
     *     path="/admin/workers/{workerId}/presets/{presetId}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a worker`s preset",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="workerId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Preset id",
     *         in="path",
     *         name="presetId",
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
     *         @OA\JsonContent(ref="#/components/schemas/PresetResource")
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
     *     path="/admin/workers/{workerId}/presets/{presetId}",
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
     *         name="presetId",
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
     *         @OA\JsonContent(ref="#/components/schemas/PresetResource")
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
     * Update user`s preset.
     *
     * @param  UserPresetUpdateRequest  $request
     * @param  int  $user
     * @param  int  $preset
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function update(UserPresetUpdateRequest $request, int $user, int $preset): JsonResource
    {
        $user = $this->userRepository->find($user);
        $preset = $this->presetRepository->find($preset);

        $this->authorize('update', $preset);

        return new PresetResource(
            $this->userPresetStorage->update($user, $preset, $request->validated())
        );
    }

    /**
     * @OA\Delete(
     *     path="/admin/workers/{workerId}/presets/{presetId}",
     *     tags={"Worker"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a worker`s preset",
     *     @OA\Parameter(
     *         description="Worker id",
     *         in="path",
     *         name="workerId",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Parameter(
     *         description="Preset id",
     *         in="path",
     *         name="presetId",
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
     * Delete user`s preset.
     *
     * @param  int  $user
     * @param  int  $preset
     * @return Response
     *
     * @throws AuthorizationException
     * @throws Exception
     */
    public function destroy(int $user, int $preset): Response
    {
        $user = $this->userRepository->find($user);
        $preset = $this->presetRepository->find($preset);

        $this->authorize('delete', $preset);

        $this->userPresetStorage->destroy($user, $preset);

        return response()->noContent();
    }
}
