<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Communication\Dto\CallDto;
use Modules\Communication\Http\Requests\CallCreateRequest;
use Modules\Communication\Http\Requests\CallUpdateRequest;
use Modules\Communication\Http\Resources\CallResource;
use Modules\Communication\Repositories\CallRepository;
use Modules\Communication\Services\CallStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class CallController extends Controller
{
    /**
     * @param  CallRepository  $repository
     * @param  CallStorage  $storage
     */
    public function __construct(
        protected CallRepository $repository,
        protected CallStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/call",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationCall"},
     *      summary="Get call list",
     *      description="Returns call list data.",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CallCollection")
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
     * Display call list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', Call::class);

        return CallResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Post(
     *      path="/admin/communication/call",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationCall"},
     *      summary="Store call",
     *      description="Returns call data.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "user_id",
     *                     "provider_id",
     *                     "text",
     *                 },
     *                 @OA\Property(property="user_id", type="integer", description="User ID"),
     *                 @OA\Property(property="provider_id", type="integer", description="Communication provider ID"),
     *                 @OA\Property(property="duration", type="integer", description="Duration sec."),
     *                 @OA\Property(property="text", type="string", description="Text"),
     *                 @OA\Property(property="status", type="integer", description="Status"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CallResource")
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
     * Store call.
     *
     * @param  CallCreateRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(CallCreateRequest $request): JsonResource
    {
        $this->authorize('create', Call::class);

        return new CallResource(
            $this->storage->store(CallDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/call/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationCall"},
     *      summary="Get call",
     *      description="Returns call data.",
     *      @OA\Parameter(
     *         description="Call ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CallResource")
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
     * Show the Call.
     *
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $call = $this->repository->find($id);

        $this->authorize('view', $call);

        return new CallResource($call);
    }

    /**
     * @OA\Put(
     *      path="/admin/communication/call/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationCall"},
     *      summary="Update call",
     *      description="Returns call data.",
     *      @OA\Parameter(
     *         description="Call ID",
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
     *                     "provider_id",
     *                     "text",
     *                 },
     *                 @OA\Property(property="provider_id", type="integer", description="Communication provider ID"),
     *                 @OA\Property(property="duration", type="integer", description="Duration sec."),
     *                 @OA\Property(property="text", type="string", description="Text"),
     *                 @OA\Property(property="status", type="integer", description="Status"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CallResource")
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
     *      path="/admin/communication/call/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationCall"},
     *      summary="Update call",
     *      description="Returns call data.",
     *      @OA\Parameter(
     *         description="Call ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="provider_id", type="integer", description="Communication provider ID"),
     *                 @OA\Property(property="duration", type="integer", description="Duration sec."),
     *                 @OA\Property(property="text", type="string", description="Text"),
     *                 @OA\Property(property="status", type="integer", description="Status"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/CallResource")
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
     * Update the Call.
     *
     * @param  CallUpdateRequest  $request
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(CallUpdateRequest $request, int $id): JsonResource
    {
        $call = $this->repository->find($id);

        $this->authorize('update', $call);

        return new CallResource(
            $this->storage->update(
                $call,
                CallDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/communication/call/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationCall"},
     *      summary="Delete call",
     *      @OA\Parameter(
     *         description="Call ID",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *         response=204,
     *         description="No content"
     *      ),
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
     * Remove the call.
     *
     * @param  int  $id
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $call = $this->repository->find($id);

        $this->authorize('delete', $call);

        $this->storage->delete($call);

        return response()->noContent();
    }
}
