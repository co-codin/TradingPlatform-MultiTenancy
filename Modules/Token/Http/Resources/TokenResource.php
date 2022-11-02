<?php

namespace Modules\Token\Http\Resources;

use App\Http\Resources\BaseJsonResource;
use Modules\Worker\Http\Resources\WorkerResource;

class TokenResource extends BaseJsonResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'worker' => new WorkerResource($this->whenLoaded('worker')),
        ]);
    }
}
