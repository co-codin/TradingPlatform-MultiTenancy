<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Modules\Communication\Dto\ChatMessageDto;
use Modules\Communication\Events\ChatEvent;
use Modules\Communication\Events\ChatHistoryEvent;
use Modules\Communication\Http\Requests\ChatHistoryRequest;
use Modules\Communication\Http\Requests\ChatMessageCreateRequest;
use Modules\Communication\Http\Resources\Chat\ChatMessageResource;
use Modules\Communication\Http\Resources\Chat\ChatUserResource;
use Modules\Communication\Models\ChatMessage;
use Modules\Communication\Repositories\ChatRepository;
use Modules\Communication\Services\ChatStorage;
use OpenApi\Annotations as OA;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class ChatController extends Controller
{
    /**
     * @param  ChatRepository  $repository
     * @param  ChatStorage  $storage
     */
    public function __construct(
        protected ChatRepository $repository,
        protected ChatStorage $storage,
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
     * Store email tempaltes.
     *
     * @param  ChatHistoryRequest  $request
     * @return Response
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function history(ChatHistoryRequest $request): Response
    {
        $this->authorize('view', ChatMessage::class);

        $customer_id = $request->validated('customer_id');

        $return = ChatMessageResource::collection($this->repository->all()->where('customer_id', $customer_id));

        ChatHistoryEvent::dispatch($request->user()->id, $customer_id, $return);

        return response()->noContent();
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
     *                 @OA\Property(property="customer_id", type="integer", description="Conversation ID"),
     *                 @OA\Property(property="message", type="string", description="Conversation message"),
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
     * Store email tempaltes.
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

        $customer_id = $request->validated('customer_id');

        ChatEvent::dispatch($request->user()->id, $customer_id, $return);

        return response()->noContent();
    }
}
