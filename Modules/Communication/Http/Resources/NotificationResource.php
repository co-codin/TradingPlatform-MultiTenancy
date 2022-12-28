<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Communication\Models\DatabaseNotification;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\Customer\Models\Customer;
use Modules\User\Http\Resources\UserResource;
use Modules\User\Models\User;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Notification",
 *     title="Notification",
 *     description="Notification model",
 *     required={
 *         "id",
 *         "notifiable_id",
 *         "notifiable_type",
 *         "data",
 *         "read_at",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Xml(name="Notification"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         required={
 *             "subject",
 *             "text",
 *             "sender_id",
 *         },
 *         @OA\Property(property="subject", type="string", description="Subject of notification"),
 *         @OA\Property(property="text", type="string", description="Text of notification"),
 *         @OA\Property(property="sender_id", type="string", description="Worker sender ID"),
 *         @OA\Property(property="template_id", type="string", description="Notification template ID"),
 *     ),
 *     @OA\Property(property="notifiable_id", type="integer", description="Notification recipient ID"),
 *     @OA\Property(property="notifiable_type", type="string", description="Notification recipient type"),
 *     @OA\Property(property="read_at", type="string", format="date-time", readOnly="true"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly="true"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly="true"),
 *     @OA\Property(property="sender", type="object", ref="#/components/schemas/Worker"),
 *     @OA\Property(property="notifiable", type="object", oneOf={
 *             @OA\Schema(ref="#/components/schemas/Worker"), @OA\Schema(ref="#/components/schemas/Customer")
 *         }
 *     ),
 * ),
 *
 * @OA\Schema (
 *     schema="NotificationCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Notification")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="NotificationResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Notification"
 *     )
 * )
 * @mixin DatabaseNotification
 */
class NotificationResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $notifiable = $this->whenLoaded('notifiable');
        $resource = match ($notifiable::class) {
            Customer::class => new CustomerResource($notifiable),
            User::class => new UserResource($notifiable),
            default => null
        };

        return array_merge(parent::toArray($request), [
            'sender' => new UserResource($this->whenLoaded('sender')),
            'notifiable' => $resource,
        ]);
    }
}
