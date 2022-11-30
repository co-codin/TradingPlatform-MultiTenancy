<?php

namespace Modules\Role\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

final class RoleCollection extends ResourceCollection
{
    /**
     * {@inheritdoc }
     */
    public $collects = RoleResource::class;

    /**
     * {@inheritDoc}
     */
    public function toArray($request): array|\JsonSerializable|\Illuminate\Contracts\Support\Arrayable
    {
        return RoleResource::collection($this->collection);
    }

    /**
     * {@inheritDoc}
     */
    public function with($request): array
    {
        return array_merge_recursive(parent::with($request), [
            'meta' => $this->resource->getOptions(),
        ]);
    }
}
