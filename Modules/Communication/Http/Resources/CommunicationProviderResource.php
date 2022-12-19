<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Communication\Models\CommunicationProvider;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CommunicationProvider",
 *     title="CommunicationProvider",
 *     description="CommunicationProvider model",
 *     required={
 *         "id",
 *         "name",
 *         "created_at",
 *         "updated_at",
 *     },
 *     @OA\Xml(name="CommunicationProvider"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Any name"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly="true",),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly="true",),
 * ),
 *
 * @OA\Schema (
 *     schema="CommunicationProviderCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CommunicationProvider")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="CommunicationProviderResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/CommunicationProvider"
 *     )
 * )
 * @mixin CommunicationProvider
 */
class CommunicationProviderResource extends BaseJsonResource
{
}
