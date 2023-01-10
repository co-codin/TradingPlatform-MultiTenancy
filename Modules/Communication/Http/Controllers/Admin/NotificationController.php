<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Notifications\Notifiable;
use Modules\Communication\Enums\NotifiableType;
use Modules\Communication\Events\NotificationEvent;
use Modules\Communication\Http\Requests\NotificationSendRequest;
use Modules\Communication\Http\Resources\NotificationResource;
use Modules\Communication\Http\Resources\NotificationTemplateResource;
use Modules\Communication\Models\DatabaseNotification;
use Modules\Communication\Models\NotificationTemplate;
use Modules\Communication\Notifications\CustomNotification;
use Modules\Communication\Repositories\NotificationRepository;
use Modules\Customer\Repositories\CustomerRepository;
use Modules\User\Repositories\UserRepository;
use OpenApi\Annotations as OA;

final class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationRepository $repository,
        private readonly CustomerRepository $customerRepository,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/notifications",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Get sent notification list",
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
     * Display sent notification list.
     *
     * @return AnonymousResourceCollection
     *
     * @throws AuthorizationException
     */
    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', NotificationTemplate::class);

        return NotificationTemplateResource::collection($this->repository->jsonPaginate());
    }

    /**
     * @OA\Get(
     *      path="/admin/communication/notifications/{id}",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Get sent notification",
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
     *          response=400,
     *          description="Bad Request"
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
     * Show the sent notification.
     *
     * @param  int  $id
     * @return NotificationResource
     *
     * @throws AuthorizationException
     */
    public function show(int $id): NotificationResource
    {
        $notification = $this->repository->find($id);

        $this->authorize('view', $notification);

        return new NotificationResource($notification);
    }

    /**
     * @OA\Post(
     *      path="/admin/communication/notifications/send",
     *      security={ {"sanctum": {} }},
     *      tags={"Communication"},
     *      summary="Send notification to customer",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "notifiable_type",
     *                     "notifiable_id",
     *                     "subject",
     *                     "text",
     *                 },
     *                 @OA\Property(property="notifiable_type", type="string", enum={"customer", "user"}, description="Notification recipient type"),
     *                 @OA\Property(property="notifiable_id", type="integer", description="Notification recipient ID"),
     *                 @OA\Property(property="subject", type="string", description="Subject of notification"),
     *                 @OA\Property(property="text", type="string", description="Text of notification"),
     *                 @OA\Property(property="immediately", type="boolean", description="Should the notification be sent immediately?"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *         response=204,
     *         description="No content"
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
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
     * Send notification.
     *
     * @param  NotificationSendRequest  $request
     * @return Response
     *
     * @throws AuthorizationException
     */
    public function send(NotificationSendRequest $request): Response
    {
        $this->authorize('send', DatabaseNotification::class);

        $repository = match ($request->validated('notifiable_type')) {
            NotifiableType::USER => $this->userRepository,
            NotifiableType::CUSTOMER => $this->customerRepository,
        };

        /** @var Notifiable $notifiable */
        $notifiable = $repository->find($request->validated('notifiable_id'));

        $notification = new CustomNotification(
            $request->validated('subject'),
            $request->validated('text'),
            $request->user()->id
        );

        $request->validated('immediately') ? $notifiable->notifyNow($notification) : $notifiable->notify($notification);

        NotificationEvent::dispatch(
            $request->validated('notifiable_type'),
            $request->validated('notifiable_id'),
            [
                'count' => $notifiable->unreadNotifications->count(),
                'notification' => $notifiable->unreadNotifications->first(),
            ]
        );

        return response()->noContent();
    }
}
