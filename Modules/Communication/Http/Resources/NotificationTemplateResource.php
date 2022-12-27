<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Communication\Models\NotificationTemplate;
use Modules\User\Http\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="NotificationTemplate",
 *     title="NotificationTemplate",
 *     description="NotificationTemplate model",
 *     required={
 *         "id",
 *         "user_id",
 *         "data",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Xml(name="NotificationTemplate"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="user_id", type="integer", example="Worker ID"),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         required={
 *             "subject",
 *             "text",
 *         },
 *         @OA\Property(property="subject", type="string", description="Subject of notification template"),
 *         @OA\Property(property="text", type="string", description="Text of notification template"),
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly="true",),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly="true",),
 * ),
 *
 * @OA\Schema (
 *     schema="NotificationTemplateCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/NotificationTemplate")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="NotificationTemplateResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/NotificationTemplate"
 *     )
 * )
 * @mixin NotificationTemplate
 */
class NotificationTemplateResource extends BaseJsonResource
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
        ]);
    }
}
