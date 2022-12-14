<?php

declare(strict_types=1);

namespace Modules\Token\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Token\Models\Token;
use Modules\User\Http\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Token",
 *     title="Token",
 *     description="Token model",
 *     required={
 *         "id",
 *         "token",
 *         "user_id",
 *         "ip",
 *         "created_at",
 *         "updated_at",
 *         "deleted_at",
 *     },
 *     @OA\Xml(name="Token"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="user_id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="user", type="object", ref="#/components/schemas/Worker", readOnly="true"),
 *     @OA\Property(property="token", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="ip", type="string", format="ipv4"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly="true",),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly="true",),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", readOnly="true",),
 * ),
 *
 * @OA\Schema (
 *     schema="TokenCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Token")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="TokenResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Token"
 *     )
 * )
 * @mixin Token
 */
final class TokenResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'user' => new UserResource($this->whenLoaded('user')),
        ]);
    }
}
