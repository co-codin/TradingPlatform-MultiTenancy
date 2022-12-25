<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources\Chat;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Communication\Models\ChatMessage;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ChatCustomerMessage",
 *     title="ChatCustomerMessage",
 *     description="ChatMessage model",
 *     @OA\Xml(name="ChatMessage"),
 *     @OA\Property(property="id", type="integer", description="Customer ID"),
 *     @OA\Property(property="first_name", type="string", description="Customer first name"),
 *     @OA\Property(property="last_name", type="string", description="Customer last name"),
 *     @OA\Property(property="gender", type="integer", description="Customer gender"),
 * ),
 *
 * @OA\Schema (
 *     schema="ChatCustomerMessageCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ChatCustomerMessage")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="ChatCustomerMessageResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/ChatCustomerMessage"
 *     )
 * )
 *
 * Class ChatCustomerResource
 *
 * @mixin ChatMessage
 */
class ChatCustomerResource extends BaseJsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'gender' => $this->gender,
        ];
    }
}
