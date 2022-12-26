<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Resources\Chat;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Customer\Models\CustomerChatMessage;
use Modules\User\Http\Resources\AuthUserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CustomerChatMessage",
 *     title="CustomerChatMessage",
 *     description="CustomerChatMessage model",
 *     required={
 *         "id",
 *         "user_id",
 *         "customer_id",
 *         "message",
 *         "initiator_id",
 *         "initiator_type",
 *         "read",
 *     },
 *     @OA\Xml(name="CustomerChatMessage"),
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="message", type="string", example="Message"),
 *     @OA\Property(property="initiator_id", type="integer", example="Message owner ID"),
 *     @OA\Property(property="initiator_type", type="string", example="Message owner Type"),
 *     @OA\Property(property="read", type="integer", example="Read status 0 or 1"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="user", ref="#/components/schemas/CustomerChatUserMessage"),
 *     @OA\Property(property="customer", ref="#/components/schemas/CustomerChatCustomerMessage"),
 * ),
 *
 * @OA\Schema (
 *     schema="CustomerChatMessageCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CustomerChatMessage")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="CustomerChatMessageResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/CustomerChatMessage"
 *     )
 * )
 * @mixin CustomerChatMessage
 */
class CustomerChatMessageResource extends BaseJsonResource
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
            'user' => new CustomerChatUserResource($this->user),
            'customer' => new CustomerChatCustomerResource($this->customer),
        ];
    }
}
