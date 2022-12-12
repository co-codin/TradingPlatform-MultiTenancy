<?php

declare(strict_types=1);

namespace Modules\TelephonyProvider\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\TelephonyProvider\Dto\TelephonyProviderDto;
use Modules\TelephonyProvider\Http\Requests\TelephonyProviderStoreRequest;
use Modules\TelephonyProvider\Http\Requests\TelephonyProviderUpdateRequest;
use Modules\TelephonyProvider\Http\Resources\TelephonyProviderResource;
use Modules\TelephonyProvider\Models\TelephonyProvider;
use Modules\TelephonyProvider\Repositories\TelephonyProviderRepository;
use Modules\TelephonyProvider\Services\TelephonyProviderStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class TelephonyProviderController extends Controller
{
    public function __construct(
        protected TelephonyProviderRepository $repository,
        protected TelephonyProviderStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/telephony/providers",
     *      security={ {"sanctum": {} }},
     *      tags={"TelephonyProvider"},
     *      summary="Get telephony provider list",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TelephonyProviderCollection")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * Display telephony provider list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', TelephonyProvider::class);

        return TelephonyProviderResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Post(
     *      path="/admin/telephony/providers",
     *      security={ {"sanctum": {} }},
     *      tags={"TelephonyProvider"},
     *      summary="Store telephony provider",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Name of telephony provider"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TelephonyProviderResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * Store telephony provider.
     *
     * @param  TelephonyProviderStoreRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(TelephonyProviderStoreRequest $request): JsonResource
    {
        $this->authorize('create', TelephonyProvider::class);

        return new TelephonyProviderResource(
            $this->storage->store(TelephonyProviderDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/telephony/providers/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"TelephonyProvider"},
     *      summary="Get telephony provider",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TelephonyProviderResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * Show the telephony provider.
     *
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $telephonyProvider = $this->repository->find($id);

        $this->authorize('view', $telephonyProvider);

        return new TelephonyProviderResource($telephonyProvider);
    }

    /**
     * @OA\Put(
     *      path="/admin/telephony/providers/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"TelephonyProvider"},
     *      summary="Update telephony provider",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Name of telephony provider"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TelephonyProviderResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * ),
     * @OA\Patch(
     *      path="/admin/telephony/providers/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"TelephonyProvider"},
     *      summary="Update telephony provider",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", description="Name of telephony provider"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TelephonyProviderResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * Update the telephony provider.
     *
     * @param  TelephonyProviderUpdateRequest  $request
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(TelephonyProviderUpdateRequest $request, int $id): JsonResource
    {
        $telephonyProvider = $this->repository->find($id);

        $this->authorize('update', $telephonyProvider);

        return new TelephonyProviderResource(
            $this->storage->update(
                $telephonyProvider,
                TelephonyProviderDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/telephony/providers/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"TelephonyProvider"},
     *      summary="Delete telephony provider",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TelephonyProviderResource")
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     *
     * Remove the telephony provider.
     *
     * @param  int  $id
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $telephonyProvider = $this->repository->find($id);

        $this->authorize('delete', $telephonyProvider);

        $this->storage->delete($telephonyProvider);

        return response()->noContent();
    }
}
