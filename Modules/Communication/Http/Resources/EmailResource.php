<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Communication\Models\Email;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Http\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * Mail.
 *
 * @OA\Schema(
 *     schema="Email",
 *     title="Email",
 *     description="Email model",
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
 *     schema="EmailCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Email")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="EmailResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Email"
 *     )
 * )
 *
 * Class EmailResource
 *
 * @mixin Email
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
        return [
            'id' => $this->id,
            'subject' => $this->subject,
            'body' => $this->body,
            'sent_by_system' => $this->sent_by_system,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'email_template_id' => $this->email_template_id,
            'email_template' => new EmailTemplatesResource($this->template),
            'user' => new AuthUserResource($this->user),
        ];
    }
}
