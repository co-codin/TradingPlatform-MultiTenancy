<?php

declare(strict_types=1);

namespace App\Services\Storage\Traits;

use Illuminate\Database\Eloquent\Model;

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

        if (! isset($data[$key])) {
            return [];
        }

        $modelData = [];

        foreach ($data[$key] as $datum) {
            if (isset($datum['id'])) {
                if (isset($datum['pivot'])) {
                    $modelData[$datum['id']] ??= $datum['pivot'];
                } else {
                    $modelData[] = $datum['id'];
                }
            }
        }

        return $model->{$relation}()->sync($modelData);
    }
}
