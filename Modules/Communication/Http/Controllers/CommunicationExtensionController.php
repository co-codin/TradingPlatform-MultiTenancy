<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Communication\Dto\CommunicationExtensionDto;
use Modules\Communication\Http\Requests\CommunicationExtensionBulkReplaceRequest;
use Modules\Communication\Http\Requests\CommunicationExtensionStoreRequest;
use Modules\Communication\Http\Requests\CommunicationExtensionUpdateRequest;
use Modules\Communication\Http\Resources\CommunicationExtensionResource;
use Modules\Communication\Models\CommunicationExtension;
use Modules\Communication\Repositories\CommunicationExtensionRepository;
use Modules\Communication\Services\CommunicationExtensionStorage;
use Modules\User\Models\User;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class CommunicationExtensionController extends Controller
{
    public function __construct(
        private readonly CommunicationExtensionRepository $repository,
        private readonly CommunicationExtensionStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/extensions",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Get communication extension list",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationExtensionCollection")
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
     * Display communication extension list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', CommunicationExtension::class);

        return CommunicationExtensionResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/extensions/all",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Get communication extensions list all",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationExtensionCollection")
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
     * Display communication extensions list all.
     *
     * @throws AuthorizationException
     */
    public function all(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', CommunicationExtension::class);

        $extensions = $this->repository->all();

        return CommunicationExtensionResource::collection($extensions);
    }

    /**
     * @OA\Post(
     *      path="/admin/communication/extensions",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Store communication extension",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                     "user_id",
     *                     "provider_id",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Name of communication extension"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="provider_id", type="integer"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationExtensionResource")
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
     * Store communication extension.
     *
     * @param  CommunicationExtensionStoreRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(CommunicationExtensionStoreRequest $request): JsonResource
    {
        $this->authorize('create', CommunicationExtension::class);

        return new CommunicationExtensionResource(
            $this->storage->store(CommunicationExtensionDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/extensions/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Get communication extension",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationExtensionResource")
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
     * Show the communication extension.
     *
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $communicationExtension = $this->repository->find($id);

        $this->authorize('view', $communicationExtension);

        return new CommunicationExtensionResource($communicationExtension);
    }

    /**
     * @OA\Put(
     *      path="/admin/communication/extensions/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Update communication extension",
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
     *                     "user_id",
     *                     "provider_id",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Name of communication extension"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="provider_id", type="integer"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationExtensionResource")
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
     *      path="/admin/communication/extensions/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Update communication extension",
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
     *                 @OA\Property(property="name", type="string", description="Name of communication extension"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="provider_id", type="integer"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationExtensionResource")
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
     * Update the communication extension.
     *
     * @param  CommunicationExtensionUpdateRequest  $request
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(CommunicationExtensionUpdateRequest $request, int $id): JsonResource
    {
        $communicationExtension = $this->repository->find($id);

        $this->authorize('update', $communicationExtension);

        return new CommunicationExtensionResource(
            $this->storage->update(
                $communicationExtension,
                CommunicationExtensionDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/communication/extensions/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Delete communication extension",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CommunicationExtensionResource")
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
     * Remove the communication extension.
     *
     * @param  int  $id
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $communicationExtension = $this->repository->find($id);

        $this->authorize('delete', $communicationExtension);

        $this->storage->delete($communicationExtension);

        return response()->noContent();
    }

    /**
     * @OA\Put(
     *     path="/admin/communication/extensions/bulk-replace-by-worker",
     *     security={ {"sanctum": {} }},
     *     tags={"Communication"},
     *     summary="Replace communication extensions by worker",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "user_id",
     *                     "extensions",
     *                 },
     *                 @OA\Property(property="user_id", type="integer", description="Worker ID"),
     *                 @OA\Property(
     *                     property="extensions",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         required={
     *                             "name",
     *                             "provider_id",
     *                         },
     *                         @OA\Property(property="name", type="string", description="Name of communication extension"),
     *                         @OA\Property(property="provider_id", type="integer", description="Communication Provider ID"),
     *                     )
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/CommunicationExtensionCollection")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Content"
     *     )
     * ),
     *
     * @param  CommunicationExtensionBulkReplaceRequest  $request
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function bulkReplaceByUser(CommunicationExtensionBulkReplaceRequest $request): AnonymousResourceCollection
    {
        $this->authorize('create', CommunicationExtension::class);

        foreach ($this->repository->findWhere(['user_id' => $request->validated('user_id')]) as $extension) {
            $this->authorize('delete', $extension);
        }

        $this->storage->replaceByUserId($request->validated('user_id'), $request->validated('extensions', []));

        return CommunicationExtensionResource::collection(
            $this->repository->findWhere(['user_id' => $request->validated('user_id')])
        );
    }
}
