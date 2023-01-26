<?php

declare(strict_types=1);

namespace App\Services\Storage\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait SyncHelper
{
    /**
     * Sync belongs to many with pivot data
     *
     * @param  Model  $model
     * @param  array  $data
     * @param  string  $key
     * @param  string|null  $relation
     * @return array
     */
    private function syncBelongsToManyWithPivot(Model $model, array $data, string $key, ?string $relation = null): array
    {
        $relation = $relation ?: $key;

        // TODO: fix pivot attrs set
        return $model->{$relation}()->sync(
            Arr::map($data[$key] ?? [], fn ($item) => [$item['id'] => Arr::except($item, 'id')])
        );
    }
}
