<?php

namespace Modules\Customer\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Customer\Models\Customer;
use Illuminate\Http\Request;
use Modules\Geo\Http\Resources\CountryResource;

/**
 * Customer.
 *
 * @OA\Schema(
 *     schema="Customer",
 *     title="Customer",
 *     description="Customer model",
 *     required={
 *         "name",
 *         "title",
 *         "color",
 *     },
 *     @OA\Xml(name="Customer"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="first_name", type="string", example="Any first name"),
 *     @OA\Property(property="last_name", type="string", example="Any last name"),
 *     @OA\Property(property="gender", type="integer", example="1"),
 *     @OA\Property(property="email", type="string", example="customer@mail.com"),
 *     @OA\Property(property="phone", type="string", example="+1 (000) 000-0000"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true),
 * ),
 *
 * @OA\Schema (
 *     schema="CustomerCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Customer")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         ref="#/components/schemas/Meta"
 *     )
 * ),
 *
 * @OA\Schema (
 *     schema="CustomerResource",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="object",
 *         ref="#/components/schemas/Customer"
 *     )
 * )
 *
 * Class CustomerResource
 *
 * @package Modules\Customer\Http\Resources
 * @mixin Customer
 */
class CustomerResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'first_name' => $this->resource->first_name,
            'last_name' => $this->resource->last_name,
            'gender' => $this->resource->gender,
            'email' => $this->resource->email,
            'phone' => $this->resource->phone,
            'country' => $this->resource->country,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'deleted_at' => $this->resource->deleted_at,
        ];
    }
}
