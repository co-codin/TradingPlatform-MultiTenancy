<?php

namespace Modules\Desk\Http\Resources;

use App\Http\Resources\BaseJsonResource;

class DeskResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'parent' => new DeskResource($this->whenLoaded('parent')),
            'ancestors' => DeskResource::collection($this->whenLoaded('ancestors')),
            'descendants' => DeskResource::collection($this->whenLoaded('descendants')),
            'children' => DeskResource::collection($this->whenLoaded('children')),
        ]);
    }
}
