<?php

declare(strict_types=1);

namespace App\Services\Storage\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Modules\User\Models\User;

trait HasBelongsToMany
{
    /**
     * Sync belongs to many with pivot data
     *
     * @param Model $model
     * @param array $data
     * @param string $key
     * @param string|null $relation
     * @return bool
     */
    private function syncBelongsToManyWithPivot(Model $model, array $data, string $key, ?string $relation = null): bool
    {
        $relation = $relation ?: $key;
        $data = Arr::map($data[$key], fn ($item) => [$item['id'] => Arr::except($item, 'id')]);

        return ! empty($data[$key]) && $model->{$relation}()->sync($data);
    }
}
