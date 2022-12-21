<?php

declare(strict_types=1);

namespace Modules\Communication\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\Request;
use Modules\Communication\Models\Call;
use Modules\User\Http\Resources\AuthUserResource;
use Modules\User\Http\Resources\UserResource;
use OpenApi\Annotations as OA;

/**
 * Mail.
 *
 * @OA\Schema(
 *     schema="Call",
 *     title="Call",
 *     description="Call model",
 *     required={
 *         "email_template_id",
 *         "subject",
 *         "body",
 *     },
 *     @OA\Xml(name="Call"),
 *     @OA\Property(property="user_id", type="integer", description="User ID"),
 *     @OA\Property(property="provider_id", type="integer", description="Communication provider ID"),
 *     @OA\Property(property="duration", type="integer", description="Duration sec."),
 *     @OA\Property(property="text", type="string", description="Text"),
 *     @OA\Property(property="status", type="integer", description="Status"),
 * ),
 *
 * @OA\Schema (
 *     schema="CallCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Call")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="CallResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Call"
 *     )
 * )
 *
 * Class CallResource
 *
 * @mixin Call
 */
class CallResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'user' => new UserResource($this->whenLoaded('user')),
            'provider' => $this->whenLoaded('provider'),
        ]);
    }
}
