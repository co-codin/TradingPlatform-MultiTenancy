<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use Modules\Communication\Dto\ChatMessageDto;
use Modules\Communication\Http\Requests\ChatHistoryRequest;
use Modules\Communication\Http\Requests\ChatMessageCreateRequest;
use Modules\Communication\Http\Resources\Chat\ChatMessageResource;
use Modules\Communication\Models\ChatMessage;
use Modules\Communication\Repositories\ChatRepository;
use Modules\Communication\Services\ChatEvents;
use Modules\Communication\Services\ChatNotifications;
use Modules\Communication\Services\ChatStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class ChatController extends Controller
{
    /**
     * @param  ChatRepository  $repository
     * @param  ChatStorage  $storage
     * @param  ChatEvents  $chatEvents
     * @param  ChatNotifications  $chatNotifications
     */
    public function __construct(
        protected ChatRepository $repository,
        protected ChatStorage $storage,
        protected ChatEvents $chatEvents,
        protected ChatNotifications $chatNotifications,
    ) {
    }

    /**
     * @OA\Post(
     *      path="/admin/communication/chat-message-history",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationChat"},
     *      summary="Chat message history",
     *      description="Returns chat data.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "customer_id",
     *                 },
     *                 @OA\Property(property="customer_id", type="integer", description="Customer ID"),
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", ref="#/components/schemas/ChatMessageCollection"),
     *              @OA\Property(property="user", type="integer"),
     *          ),
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
     * @param  ChatHistoryRequest  $request
     * @return array
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function history(ChatHistoryRequest $request): array
    {
        $this->authorize('view', ChatMessage::class);

        $this->storage->updateDelivery((int) $request->validated('customer_id'));

        $this->chatNotifications->execution((int) $request->validated('customer_id'));

        return [
            'message' => ChatMessageResource::collection($this->repository->all()->where('customer_id', $request->validated('customer_id'))),
            'user' => $request->user()->id,
        ];
    }

    /**
     * @OA\Post(
     *      path="/admin/communication/chat-message-send",
     *      security={ {"sanctum": {} }},
     *      tags={"CommunicationChat"},
     *      summary="Store chat message",
     *      description="Returns chat message data.",
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={
     *                     "customer_id",
     *                     "message",
     *                 },
     *                 @OA\Property(property="customer_id", type="integer", description="Customer ID"),
     *                 @OA\Property(property="message", type="string", description="Message"),
     *             )
     *         )
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
     * @param  ChatMessageCreateRequest  $request
     * @return Response
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(ChatMessageCreateRequest $request): Response
    {
        $this->authorize('create', ChatMessage::class);

        $return = new ChatMessageResource(
            $this->storage->store($request->user(), ChatMessageDto::fromFormRequest($request)),
        );

        $this->chatEvents->execution((int) $request->validated('customer_id'), $request->user()->id, $return);

        return response()->noContent();
    }
}
