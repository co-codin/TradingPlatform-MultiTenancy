<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\CommunicationProvider\Dto\CommunicationProviderDto;
use Modules\CommunicationProvider\Http\Requests\CommunicationProviderStoreRequest;
use Modules\CommunicationProvider\Http\Requests\CommunicationProviderUpdateRequest;
use Modules\CommunicationProvider\Http\Resources\CommunicationProviderResource;
use Modules\CommunicationProvider\Models\CommunicationProvider;
use Modules\CommunicationProvider\Repositories\CommunicationProviderRepository;
use Modules\CommunicationProvider\Services\CommunicationProviderStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class CommunicationProviderController extends Controller
{
    public function __construct(
        private readonly CommunicationProviderRepository $repository,
        private readonly CommunicationProviderStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/providers",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationProvider"},
     *      summary="Get communication provider list",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationProviderCollection")
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
     * Display communication provider list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', CommunicationProvider::class);

        return CommunicationProviderResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/providers/all",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationProvider"},
     *      summary="Get communication provider list all",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationProviderCollection")
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
     * Display communication provider list all.
     *
     * @throws AuthorizationException
     */
    public function all(): JsonResource
    {
        $this->authorize('viewAny', CommunicationProvider::class);

        $providers = $this->repository->all();

        return CommunicationProviderResource::collection($providers);
    }

    /**
     * @OA\Post(
     *      path="/admin/communication/providers",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationProvider"},
     *      summary="Store communication provider",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Name of communication provider"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationProviderResource")
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
     * Store communication provider.
     *
     * @param  CommunicationProviderStoreRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(CommunicationProviderStoreRequest $request): JsonResource
    {
        $this->authorize('create', CommunicationProvider::class);

        return new CommunicationProviderResource(
            $this->storage->store(CommunicationProviderDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/providers/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationProvider"},
     *      summary="Get communication provider",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationProviderResource")
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
     * Show the communication provider.
     *
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $communicationProvider = $this->repository->find($id);

        $this->authorize('view', $communicationProvider);

        return new CommunicationProviderResource($communicationProvider);
    }

    /**
     * @OA\Put(
     *      path="/admin/communication/providers/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationProvider"},
     *      summary="Update communication provider",
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
     *                 @OA\Property(property="name", type="string", description="Name of communication provider"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationProviderResource")
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
     *      path="/admin/communication/providers/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationProvider"},
     *      summary="Update communication provider",
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
     *                 @OA\Property(property="name", type="string", description="Name of communication provider"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationProviderResource")
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
     * Update the communication provider.
     *
     * @param  CommunicationProviderUpdateRequest  $request
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(CommunicationProviderUpdateRequest $request, int $id): JsonResource
    {
        $communicationProvider = $this->repository->find($id);

        $this->authorize('update', $communicationProvider);

        return new CommunicationProviderResource(
            $this->storage->update(
                $communicationProvider,
                CommunicationProviderDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/communication/providers/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationProvider"},
     *      summary="Delete communication provider",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationProviderResource")
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
     * Remove the communication provider.
     *
     * @param  int  $id
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $communicationProvider = $this->repository->find($id);

        $this->authorize('delete', $communicationProvider);

        $this->storage->delete($communicationProvider);

        return response()->noContent();
    }
}
