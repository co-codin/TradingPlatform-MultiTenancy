<?php

declare(strict_types=1);

namespace Modules\Config\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Config\Dto\ConfigTypeDto;
use Modules\Config\Http\Requests\ConfigTypeCreateRequest;
use Modules\Config\Http\Requests\ConfigTypeUpdateRequest;
use Modules\Config\Http\Resources\ConfigTypeResource;
use Modules\Config\Models\ConfigType;
use Modules\Config\Repositories\ConfigTypeRepository;
use Modules\Config\Services\ConfigTypeStorage;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class ConfigTypeController extends Controller
{
    /**
     * @param ConfigTypeStorage $configTypeStorage
     * @param ConfigTypeRepository $configTypeRepository
     */
    final public function __construct(
        protected ConfigTypeStorage $configTypeStorage,
        protected ConfigTypeRepository $configTypeRepository,
    ) {
//        $this->authorizeResource(ConfigType::class);
    }

    /**
     * @OA\Get(
     *     path="/admin/configs/types",
     *     tags={"ConfigType"},
     *     security={ {"sanctum": {} }},
     *     summary="Get config types",
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/ConfigTypeCollection")
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
     * Index config type.
     *
     * @return JsonResource
     * @throws AuthorizationException
     */
    final public function index(): JsonResource
    {
        $this->authorize('viewAny', ConfigType::class);

        return ConfigTypeResource::collection(
            $this->configTypeRepository->jsonPaginate()
        );
    }

    /**
     * @OA\Get(
     *     path="/admin/configs/types/{id}",
     *     tags={"ConfigType"},
     *     security={ {"sanctum": {} }},
     *     summary="Get config type data",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Config type ID",
     *          required=true,
     *          @OA\Schema (type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(ref="#/components/schemas/ConfigTypeResource")
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
     * Show config type.
     *
     * @param int $configType
     * @return JsonResource
     * @throws AuthorizationException
     */
    final public function show(int $configType): JsonResource
    {
        $configType = $this->configTypeRepository->find($configType);

        $this->authorize('view', $configType);

        return new ConfigTypeResource($configType);
    }

    /**
     * @OA\Post(
     *     path="/admin/configs/types",
     *     tags={"ConfigType"},
     *     security={ {"sanctum": {} }},
     *     summary="Add a new config type",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Config types`s name"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/ConfigTypeResource")
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
     * Store config type.
     *
     * @param ConfigTypeCreateRequest $request
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    final public function store(ConfigTypeCreateRequest $request): JsonResource
    {
        $this->authorize('create', ConfigType::class);

        return new ConfigTypeResource(
            $this->configTypeStorage->store(ConfigTypeDto::fromFormRequest($request))
        );
    }

    /**
     * @OA\Put(
     *     path="/admin/configs/types/{id}",
     *     tags={"ConfigType"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a config type",
     *     @OA\Parameter(
     *         description="Config type ID",
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
     *                 },
     *                 @OA\Property(property="name", type="string", description="Config type`s name"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/ConfigTypeResource")
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
     *     path="/admin/configs/types/{id}",
     *     tags={"ConfigType"},
     *     security={ {"sanctum": {} }},
     *     summary="Update a config type",
     *     @OA\Parameter(
     *         description="Config type ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Config type`s name"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\JsonContent(ref="#/components/schemas/ConfigTypeResource")
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
     * Update config type.
     *
     * @param ConfigTypeUpdateRequest $request
     * @param int $configType
     * @return JsonResource
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    final public function update(ConfigTypeUpdateRequest $request, int $configType): JsonResource
    {
        $configType = $this->configTypeRepository->find($configType);

        $this->authorize('update', $configType);

        return new ConfigTypeResource(
            $this->configTypeStorage->update($configType, ConfigTypeDto::fromFormRequest($request))
        );
    }

    /**
     * @OA\Delete(
     *     path="/admin/configs/types/{id}",
     *     tags={"ConfigType"},
     *     security={ {"sanctum": {} }},
     *     summary="Delete a config type",
     *     @OA\Parameter(
     *         description="Config type ID",
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
     * Destroy config type.
     *
     * @param int $configType
     * @return Response
     * @throws AuthorizationException
     */
    final public function destroy(int $configType): Response
    {
        $configType = $this->configTypeRepository->find($configType);

        $this->authorize('delete', $configType);

        $this->configTypeStorage->delete($configType);

        return response()->noContent();
    }
}
