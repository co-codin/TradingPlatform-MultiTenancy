<?php

declare(strict_types=1);

namespace Modules\Role\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Role\Models\Column;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="Column",
 *     type="object",
 *     required={
 *         "id",
 *         "name"
 *     },
 *     @OA\Property(property="id", type="integer", description="Column ID"),
 *     @OA\Property(property="name", type="string", description="Column name"),
 * ),
 *
 * @OA\Schema (
 *     schema="ColumnCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Column")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="ColumnResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Column"
 *     )
 * )
 *
 * Class ColumnResource
 *
 * @mixin Column
 */
final class ColumnResource extends BaseJsonResource
{
}
