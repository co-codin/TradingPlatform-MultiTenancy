<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Communication\Models\EmailTemplates;
use OpenApi\Annotations as OA;

/**
 * EmailTemplates.
 *
 * @OA\Schema(
 *     schema="EmailTemplates",
 *     title="Email Templates",
 *     description="EmailTemplates model",
 *     required={
 *         "name",
 *         "body",
 *     },
 *     @OA\Xml(name="EmailTemplates"),
 *     @OA\Property(property="name", type="string", description="Email template name"),
 *     @OA\Property(property="body", type="string", description="Email template body"),
 * ),
 *
 * @OA\Schema (
 *     schema="EmailTemplatesCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/EmailTemplates")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="EmailTemplatesResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/EmailTemplates"
 *     )
 * )
 *
 * Class EmailTemplatesResource
 *
 * @mixin EmailTemplates
 */
class EmailTemplatesResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
