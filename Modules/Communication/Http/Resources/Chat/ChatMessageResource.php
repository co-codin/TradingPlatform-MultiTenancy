<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources\Chat;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Communication\Models\ChatMessage;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Http\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ChatMessage",
 *     title="ChatMessage",
 *     description="ChatMessage model",
 *     required={
 *         "customer_id",
 *         "message",
 *     },
 *     @OA\Xml(name="ChatMessage"),
 *     @OA\Property(property="customer_id", type="integer", description="Customer ID"),
 *     @OA\Property(property="body", type="string", description="Email template body"),
 * ),
 *
 * @OA\Schema (
 *     schema="ChatMessageCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ChatMessage")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="ChatMessageResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/ChatMessage"
 *     )
 * )
 * @mixin ChatMessage
 */
class ChatMessageResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'message' => $this->message,
            'initiator_id' => $this->initiator_id,
            'initiator_type' => $this->initiator_type,
            'read' => $this->read,
            'created_at' => $this->created_at,
            'user' => new ChatUserResource($this->user),
            'customer' => new ChatCustomerResource($this->customer),
        ];
    }
}
