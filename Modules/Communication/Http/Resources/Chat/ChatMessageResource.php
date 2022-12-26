<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources\Chat;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Communication\Models\ChatMessage;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ChatMessage",
 *     title="ChatMessage",
 *     description="ChatMessage model",
 *     required={
 *         "id",
 *         "user_id",
 *         "customer_id",
 *         "message",
 *         "initiator_id",
 *         "initiator_type",
 *         "read",
 *     },
 *     @OA\Xml(name="ChatMessage"),
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="message", type="string", example="Message"),
 *     @OA\Property(property="initiator_id", type="integer", example="Message owner ID"),
 *     @OA\Property(property="initiator_type", type="string", example="Message owner Type"),
 *     @OA\Property(property="read", type="integer", example="Read status 0 or 1"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="user", ref="#/components/schemas/ChatUserMessage"),
 *     @OA\Property(property="customer", ref="#/components/schemas/ChatCustomerMessage"),
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
