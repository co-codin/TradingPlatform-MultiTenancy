<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Resources\Chat;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Customer\Models\CustomerChatMessage;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CustomerChatUserMessage",
 *     title="CustomerChatUserMessage",
 *     description="CustomerChatMessage model",
 *     @OA\Xml(name="ChatMessage"),
 *     @OA\Property(property="id", type="integer", description="User ID"),
 *     @OA\Property(property="username", type="string", description="Username"),
 *     @OA\Property(property="first_name", type="string", description="Worker first name"),
 *     @OA\Property(property="last_name", type="string", description="Worker last name"),
 * ),
 * @OA\Schema (
 *     schema="CustomerChatUserMessageCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CustomerChatUserMessage")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 * @OA\Schema (
 *     schema="CustomerChatUserMessageResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/CustomerChatUserMessage"
 *     )
 * )
 *
 * Class CustomerChatUserResource
 *
 * @mixin CustomerChatMessage
 */
class CustomerChatUserResource extends BaseJsonResource
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
            'username' => $this->resource->username,
            'first_name' => $this->resource->workerInfo->first_name,
            'last_name' => $this->resource->workerInfo->last_name,
        ];
    }
}
