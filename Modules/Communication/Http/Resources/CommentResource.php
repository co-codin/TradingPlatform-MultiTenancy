<?php

namespace Modules\Communication\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Communication\Models\Comment;
use Modules\Customer\Http\Resources\CustomerResource;
use Modules\User\Http\Resources\UserResource;

/**
 * Comment.
 *
 * @OA\Schema(
 *     schema="Comment",
 *     title="Comment",
 *     description="Comment model",
 *     required={
 *         "id",
 *         "body",
 *         "user_id",
 *         "customer_id",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Xml(name="Comment"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="user_id", type="integer", example="1"),
 *     @OA\Property(property="customer_id", type="integer", example="1"),
 *     @OA\Property(property="body", type="string", example="Body text"),
 *     @OA\Property(property="position", type="integer", example="1"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="user", type="object", ref="#/components/schemas/Worker"),
 *     @OA\Property(property="customer", type="object", ref="#/components/schemas/Customer"),
 *     @OA\Property(property="attachments", type="object"),
 * ),
 *
 * @OA\Schema (
 *     schema="CommentCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Comment")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="CommentResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Comment"
 *     )
 * )
 *
 * Class CommentResource
 *
 * @mixin Comment
 */
class CommentResource extends BaseJsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'user' => UserResource::collection($this->whenLoaded('user')),
            'customer' => CustomerResource::collection($this->whenLoaded('customer')),
            'attachments' => $this->whenLoaded('attachments'),
        ]);
    }
}
