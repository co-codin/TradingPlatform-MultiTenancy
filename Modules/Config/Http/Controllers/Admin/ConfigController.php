<?php

declare(strict_types=1);

namespace Modules\Config\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Config\Dto\ConfigDto;
use Modules\Config\Http\Requests\ConfigCreateRequest;
use Modules\Config\Http\Requests\ConfigUpdateRequest;
use Modules\Config\Http\Resources\ConfigResource;
use Modules\Config\Models\Config;
use Modules\Config\Repositories\ConfigRepository;
use Modules\Config\Services\ConfigStorage;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class ConfigController extends Controller
{
    /**
     * @param ConfigStorage $configStorage
     * @param ConfigRepository $configRepository
     */
    final public function __construct(
        protected ConfigStorage $configStorage,
        protected ConfigRepository $configRepository,
    ) {}

    /**
     * @OA\Get(
     *     path="/admin/configs",
     *     tags={"Config"},
     *     security={ {"sanctum": {} }},
     *     summary="Get configs",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/ConfigCollection")
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
     * Index config.
     *
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Config::class);

        return ConfigResource::collection(
            $this->configRepository->jsonPaginate()
        );
    }

    /**
     * @OA\Get(
     *     path="/admin/configs/{id}",
     *     tags={"Config"},
     *     security={ {"sanctum": {} }},
     *     summary="Get config data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Config ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/ConfigResource")
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
     * Show config.
     *
     * @param int $config
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function show(int $config): JsonResource
    {
        $config = $this->configRepository->find($config);

        $this->authorize('view', $config);

        return new ConfigResource($config);
    }

    /**
     * @OA\Post(
     *     path="/admin/configs",
     *     tags={"Config"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new config",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "config_type_id",
     *                     "data_type",
     *                     "name",
     *                     "value",
     *                 },
     *                 @OA\Property(property="config_type_id", type="integer", description="Config type ID"),
     *                 @OA\Property(property="data_type", type="string", description="Data type"),
     *                 @OA\Property(property="name", type="string", description="Config`s name"),
     *                 @OA\Property(property="value", type="string", description="Config`s value"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/ConfigResource")
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
     * Store config.
     *
     * @param ConfigCreateRequest $request
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(ConfigCreateRequest $request): JsonResource
    {
        $this->authorize('create', Config::class);

        return new ConfigResource(
            $this->configStorage->store(ConfigDto::fromFormRequest($request))
        );
    }

    /**
     * @OA\Put(
     *     path="/admin/configs/{id}",
     *     tags={"Config"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a config",
     *     @OA\Parameter(
     *         description="Config ID",
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
     *                     "config_type_id",
     *                     "data_type",
     *                     "name",
     *                     "value",
     *                 },
     *                 @OA\Property(property="config_type_id", type="integer", description="Config type ID"),
     *                 @OA\Property(property="data_type", type="string", description="Data type"),
     *                 @OA\Property(property="name", type="string", description="Config`s name"),
     *                 @OA\Property(property="value", type="string", description="Config`s value"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/ConfigResource")
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
     *     path="/admin/configs/{id}",
     *     tags={"Config"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a config",
     *     @OA\Parameter(
     *         description="Config ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="config_type_id", type="integer", description="Config type ID"),
     *                 @OA\Property(property="data_type", type="string", description="Data type"),
     *                 @OA\Property(property="name", type="string", description="Config`s name"),
     *                 @OA\Property(property="value", type="string", description="Config`s value"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/ConfigResource")
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
     * Update config.
     *
     * @param ConfigUpdateRequest $request
     * @param int $config
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(ConfigUpdateRequest $request, int $config): JsonResource
    {
        $config = $this->configRepository->find($config);

        $this->authorize('update', $config);

        return new ConfigResource(
            $this->configStorage->update($config, ConfigDto::fromFormRequest($request))
        );
    }

    /**
     * @OA\Delete(
     *     path="/admin/configs/{id}",
     *     tags={"Config"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a config",
     *     @OA\Parameter(
     *         description="Config ID",
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
     * Destroy config.
     *
     * @param int $config
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(int $config): Response
    {
        $config = $this->configRepository->find($config);

        $this->authorize('delete', $config);

        $this->configStorage->delete($config);

        return response()->noContent();
    }
}
