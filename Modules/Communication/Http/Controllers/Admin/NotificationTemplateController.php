<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Communication\Dto\NotificationTemplateDto;
use Modules\Communication\Http\Requests\NotificationTemplateStoreRequest;
use Modules\Communication\Http\Requests\NotificationTemplateUpdateRequest;
use Modules\Communication\Http\Resources\NotificationTemplateResource;
use Modules\Communication\Models\NotificationTemplate;
use Modules\Communication\Repositories\NotificationTemplateRepository;
use Modules\Communication\Services\NotificationTemplateStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class NotificationTemplateController extends Controller
{
    public function __construct(
        private readonly NotificationTemplateRepository $repository,
        private readonly NotificationTemplateStorage $storage,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/notification-templates",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Get notification template list",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationTemplateCollection")
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
     * Display notification template list.
     *
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function index(): JsonResource
    {
        $this->authorize('viewAny', NotificationTemplate::class);

        return NotificationTemplateResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/notification-templates/all",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Get communication notification templates list all",
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationTemplateCollection")
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
     * Display communication notification templates list all.
     *
     * @throws AuthorizationException
     */
    public function all(): JsonResource
    {
        $this->authorize('viewAny', NotificationTemplate::class);

        $templates = $this->repository->all();

        return NotificationTemplateResource::collection($templates);
    }

    /**
     * @OA\Post(
     *      path="/admin/communication/notification-templates",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Store notification template",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "subject",
     *                     "text",
     *                 },
     *                 @OA\Property(property="text", type="string", description="Text of notification template"),
     *                 @OA\Property(property="subject", type="string", description="Subject of notification template"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationTemplateResource")
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
     * Store notification template.
     *
     * @param  NotificationTemplateStoreRequest  $request
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(NotificationTemplateStoreRequest $request): JsonResource
    {
        $this->authorize('create', NotificationTemplate::class);

        return new NotificationTemplateResource(
            $this->storage->store(NotificationTemplateDto::fromFormRequest($request)),
        );
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/notification-templates/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Get notification template",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationTemplateResource")
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
     * Show the notification template.
     *
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResource
    {
        $template = $this->repository->find($id);

        $this->authorize('view', $template);

        return new NotificationTemplateResource($template);
    }

    /**
     * @OA\Put(
     *      path="/admin/communication/notification-templates/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Update notification template",
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
     *                     "subject",
     *                     "text",
     *                 },
     *                 @OA\Property(property="text", type="string", description="Text of notification template"),
     *                 @OA\Property(property="subject", type="string", description="Subject of notification template"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationTemplateResource")
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
     *      path="/admin/communication/notification-templates/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Update notification template",
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
     *                 @OA\Property(property="text", type="string", description="Text of notification template"),
     *                 @OA\Property(property="subject", type="string", description="Subject of notification template"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationTemplateResource")
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
     * Update the notification template.
     *
     * @param  NotificationTemplateUpdateRequest  $request
     * @param  int  $id
     * @return JsonResource
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(NotificationTemplateUpdateRequest $request, int $id): JsonResource
    {
        $template = $this->repository->find($id);

        $this->authorize('update', $template);

        return new NotificationTemplateResource(
            $this->storage->update(
                $template,
                NotificationTemplateDto::fromFormRequest($request)
            ),
        );
    }

    /**
     * @OA\Delete(
     *      path="/admin/communication/notification-templates/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Delete notification template",
     *      @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/NotificationTemplateResource")
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
     * Remove the notification template.
     *
     * @param  int  $id
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id): Response
    {
        $template = $this->repository->find($id);

        $this->authorize('delete', $template);

        $this->storage->delete($template);

        return response()->noContent();
    }
}
