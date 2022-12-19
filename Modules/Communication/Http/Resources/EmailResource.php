<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Sale\Models\SaleStatus;
use OpenApi\Annotations as OA;

/**
 * Mail.
 *
 * @OA\Schema(
 *     schema="Mail",
 *     title="Mail",
 *     description="Mail model",
 *     required={
 *         "email_template_id",
 *         "subject",
 *         "body",
 *     },
 *     @OA\Xml(name="Email"),
 *     @OA\Property(property="email_template_id", type="integer", description="Email tempalte ID"),
 *     @OA\Property(property="subject", type="string", description="Email subject"),
 *     @OA\Property(property="body", type="string", description="Email body"),
 *     @OA\Property(property="sent_by_system", type="bool"),
 *     @OA\Property(property="user_id", type="integer", description="User ID"),
 * ),
 *
 * @OA\Schema (
 *     schema="MailCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Mail")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="MailResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Mail"
 *     )
 * )
 *
 * Class MailResource
 *
 * @mixin Mail
 */
class EmailResource extends BaseJsonResource
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
