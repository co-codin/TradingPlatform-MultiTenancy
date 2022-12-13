<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\CommunicationProvider\Models\CommunicationExtension;
use Modules\User\Http\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CommunicationExtension",
 *     title="CommunicationExtension",
 *     description="CommunicationExtension model",
 *     required={
 *         "id",
 *         "name",
 *         "user_id",
 *         "provider_id",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Xml(name="CommunicationExtension"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Any name"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="provider_id", type="integer"),
 *     @OA\Property(property="user", type="array", @OA\Items(ref="#/components/schemas/Worker")),
 *     @OA\Property(property="provider", type="array", @OA\Items(ref="#/components/schemas/CommunicationProvider")),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly="true",),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly="true",),
 * ),
 *
 * @OA\Schema (
 *     schema="CommunicationExtensionCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CommunicationExtension")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="CommunicationExtensionResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/CommunicationExtension"
 *     )
 * )
 * @mixin CommunicationExtension
 */
class CommunicationExtensionResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'user' => new UserResource($this->whenLoaded('user')),
            'provider' => new CommunicationProviderResource($this->whenLoaded('provider')),
        ]);
    }
}
