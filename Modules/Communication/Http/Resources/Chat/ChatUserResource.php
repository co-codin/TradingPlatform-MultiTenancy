<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources\Chat;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Communication\Models\ChatMessage;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ChatUserMessage",
 *     title="ChatUserMessage",
 *     description="ChatMessage model",
 *     @OA\Xml(name="ChatMessage"),
 *     @OA\Property(property="id", type="integer", description="User ID"),
 *     @OA\Property(property="username", type="string", description="Username"),
 *     @OA\Property(property="first_name", type="string", description="User first name"),
 *     @OA\Property(property="last_name", type="string", description="User last name"),
 * ),
 *
 * @OA\Schema (
 *     schema="ChatUserMessageCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ChatUserMessage")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="ChatUserMessageResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/ChatUserMessage"
 *     )
 * )
 *
 * Class ChatUserResource
 *
 * @mixin ChatMessage
 */
class ChatUserResource extends BaseJsonResource
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
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ];
    }
}
