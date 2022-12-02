<?php

declare(strict_types=1);

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Models\User;

/**
 * @OA\Schema (
 *     schema="DisplayOption",
 *     type="object",
 *     required={
 *         "id",
 *         "user_id",
 *         "name",
 *         "columns",
 *     },
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="model_id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="columns", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="user", type="array", @OA\Items(ref="#/components/schemas/Worker")),
 *     @OA\Property(property="model", type="object"),
 * ),
 *
 * @OA\Schema (
 *     schema="DisplayOptionCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/DisplayOption")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="DisplayOptionResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/DisplayOption"
 *     )
 * )
 *
 * Class DisplayOptionResource
 * @mixin User
 */
final class DisplayOptionResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    final public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'user' => new UserResource($this->whenLoaded('user')),
            'model' => $this->whenLoaded('model'),
        ]);
    }
}
