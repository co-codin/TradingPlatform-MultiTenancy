<?php

declare(strict_types=1);

namespace Modules\Splitter\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Splitter\Models\SplitterChoice;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="SplitterChoice",
 *     type="object",
 *     required={
 *         "id",
 *         "splitter_id",
 *         "type",
 *         "option_per_day",
 *         "deleted_at",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Property(property="id", type="integer", description="Splitter Choice ID"),
 *     @OA\Property(property="splitter_id", type="integer", description="Splitter id"),
 *     @OA\Property(property="type", type="integer", description="Splitter Choice type (Desk = 1, Worker = 2)", example="1"),
 *     @OA\Property(property="option_per_day", type="integer", description="Splitter Choice option per day (Percent Per Day = 1 , Cap Per Day = 2)", example="1"),
 *     @OA\Property(property="splitter", type="array", @OA\Items(ref="#/components/schemas/Splitter")),
 *     @OA\Property(property="desks", type="array", @OA\Items(ref="#/components/schemas/Desk")),
 *     @OA\Property(property="workers", type="array", @OA\Items(ref="#/components/schemas/Worker")),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", description="Date and time of deleted", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date and time of creation", example="2022-12-17 08:44:09"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date and time of last update", example="2022-12-17 08:44:09"),
 * ),
 * @OA\Schema (
 *     schema="SplitterChoiceCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/SplitterChoice")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 * @OA\Schema (
 *     schema="SplitterChoiceResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/SplitterChoice"
 *     )
 * )
 *
 * Class SplitterChoiceResource
 *
 * @mixin SplitterChoice
 */
final class SplitterChoiceResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
