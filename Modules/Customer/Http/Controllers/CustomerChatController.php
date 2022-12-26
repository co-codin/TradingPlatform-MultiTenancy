<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\Customer\Models\CustomerChatMessage;
use Modules\Customer\Services\CustomerChatEvents;
use Modules\Customer\Dto\CustomerChatMessageDto;
use Modules\Customer\Http\Requests\CustomerChatMessageCreateRequest;
use Modules\Customer\Repositories\CustomerChatRepository;
use Modules\Customer\Services\CustomerChatNotification;
use Modules\Customer\Services\CustomerChatStorage;
use Modules\Customer\Http\Resources\Chat\CustomerChatMessageResource;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class CustomerChatController extends Controller
{
    /**
     * @param  CustomerChatRepository  $repository
     * @param  CustomerChatStorage  $storage
     * @param  CustomerChatEvents  $customerChatEvents
     * @param  CustomerChatNotification  $customerChatNotification
     */
    public function __construct(
        protected CustomerChatRepository $repository,
        protected CustomerChatStorage $storage,
        protected CustomerChatEvents $customerChatEvents,
        protected CustomerChatNotification $customerChatNotification,
    ) {
    }
    /**
     * @OA\Post(
     *      path="/customer/chat-message-history",
     *      security={ {"sanctum": {} }},
     *      tags={"CustomerChat"},
     *      summary="Chat message history",
     *      description="Returns chat data.",
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", ref="#/components/schemas/CustomerChatMessageCollection"),
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
     *
     * @param  Request  $request
     * @return Array
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function history(Request $request): array
    {
        // $this->authorize('view', CustomerChatMessage::class);

        $this->storage->updateDelivery($request->user()->id);

        $this->customerChatNotification->execution($request->user()->id);

        return [
            'message' => CustomerChatMessageResource::collection($this->repository->all()->where('customer_id', $request->user()->id)),
        ];
    }
    /**
     * @OA\Post(
     *      path="/customer/chat-message-send",
     *      security={ {"sanctum": {} }},
     *      tags={"CustomerChat"},
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
     *
     * @param  CustomerChatMessageCreateRequest  $request
     * @return Response
     *
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function store(CustomerChatMessageCreateRequest $request) //: Response
    {
        // $this->authorize('create', CustomerChatMessage::class);

        $return = new CustomerChatMessageResource(
            $this->storage->store($request->user(), CustomerChatMessageDto::fromFormRequest($request)),
        );

        $this->customerChatEvents->execution($request->user()->id, $return);

        return response()->noContent();
    }
}
