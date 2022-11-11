<?php

namespace Modules\Geo\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Country collection.
 *
 * @OA\Schema(
 *     schema="CountryCollection",
 *     title="Country Collection",
 *     @OA\Xml(name="CountryCollection"),
 *     @OA\Property(
 *         title="Country Collection",
 *         property="data",
 *         type="array",
 *         description="Countries",
 *         @OA\Items(ref="#/components/schemas/Country")
 *     )
 * )
 */
class CountryCollection extends ResourceCollection
{
    /**
     * @inheritdoc
     */
    public $collects = CountryResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  Request $request
     * @return AnonymousResourceCollection
     */
    public function toArray($request): AnonymousResourceCollection
    {
        return CountryResource::collection($this->collection);
    }
}
