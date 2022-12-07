<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Models\Customer;
use Modules\Geo\Http\Resources\CountryResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema (
 *     schema="AuthCustomer",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="permissions", type="array", @OA\Items(type="string")),
 *     @OA\Property(property="role", type="object", ref="#/components/schemas/Role")
 * )
 *
 * @OA\Schema (
 *     schema="AuthCustomerCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AuthCustomer")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * )
 *
 * @OA\Schema (
 *     schema="AuthCustomerResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/AuthCustomer"
 *     )
 * )
 *
 * Class AuthCustomerResource
 * @mixin Customer
 */
final class AuthCustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => new CountryResource($this->country),
        ];
    }
}
