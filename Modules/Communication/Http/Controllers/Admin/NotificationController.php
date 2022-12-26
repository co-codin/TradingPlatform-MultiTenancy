<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Communication\Dto\NotificationDto;
use Modules\Communication\Http\Requests\NotificationStoreRequest;
use Modules\Communication\Http\Requests\NotificationUpdateRequest;
use Modules\Communication\Http\Resources\NotificationResource;
use Modules\Communication\Models\DatabaseNotification;
use Modules\Communication\Repositories\NotificationRepository;
use Modules\Communication\Services\NotificationStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationRepository $repository,
        private readonly NotificationStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/notifications",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Get notification list",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationCollection")
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
     * Display notification list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', DatabaseNotification::class);

        return NotificationResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/notifications/all",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Get communication notifications list all",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationCollection")
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
     * Display communication notifications list all.
     *
     * @throws AuthorizationException
     */
    public function all(): JsonResource
    {
        $this->authorize('viewAny', DatabaseNotification::class);

        $notifications = $this->repository->all();

        return NotificationResource::collection($notifications);
    }

    /**
     * @OA\Post(
     *      path="/admin/communication/notifications",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Store notification",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "name",
     *                 },
     *                 @OA\Property(property="name", type="string", description="Name of notification"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationResource")
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
     * Store notification.
     *
     * @param  NotificationStoreRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(NotificationStoreRequest $request): JsonResource
    {
        $this->authorize('create', DatabaseNotification::class);

        return new NotificationResource(
            $this->storage->store(NotificationDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/notifications/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Get notification",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationResource")
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
     * Show the notification.
     *
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $notification = $this->repository->find($id);

        $this->authorize('view', $notification);

        return new NotificationResource($notification);
    }

    /**
     * @OA\Put(
     *      path="/admin/communication/notifications/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Update notification",
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
     *                 @OA\Property(property="name", type="string", description="Name of notification"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationResource")
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
     *      path="/admin/communication/notifications/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Update notification",
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
     *                 @OA\Property(property="name", type="string", description="Name of notification"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationResource")
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
     * Update the notification.
     *
     * @param  NotificationUpdateRequest  $request
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(NotificationUpdateRequest $request, int $id): JsonResource
    {
        $notification = $this->repository->find($id);

        $this->authorize('update', $notification);

        return new NotificationResource(
            $this->storage->update(
                $notification,
                NotificationDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/communication/notifications/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Delete notification",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationResource")
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
     * Remove the notification.
     *
     * @param  int  $id
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $notification = $this->repository->find($id);

        $this->authorize('delete', $notification);

        $this->storage->delete($notification);

        return response()->noContent();
    }
}
