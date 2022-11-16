<?php

namespace Modules\Desk\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Geo\Http\Resources\CountryResource;
use Modules\Language\Http\Resources\LanguageResource;

class DeskResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'parent' => new DeskResource($this->whenLoaded('parent')),
            'ancestors' => DeskResource::collection($this->whenLoaded('ancestors')),
            'descendants' => DeskResource::collection($this->whenLoaded('descendants')),
            'children' => DeskResource::collection($this->whenLoaded('children')),
            'languages' => LanguageResource::collection($this->whenLoaded('languages')),
            'countries' => CountryResource::collection($this->whenLoaded('countries')),
        ]);
    }
}
