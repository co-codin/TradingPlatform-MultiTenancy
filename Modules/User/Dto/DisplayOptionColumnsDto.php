<?php

declare(strict_types=1);

namespace Modules\User\Dto;

use App\Dto\BaseDto;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class DisplayOptionColumnsDto extends BaseDto implements CastsAttributes
{
    /**
     * @param $model
     * @param  string  $key
     * @param $value
     * @param  array  $attributes
     * @return array
     */
    final public function get($model, string $key, $value, array $attributes): array
    {
        return json_decode($value, true);
    }

    /**
     * @param $model
     * @param  string  $key
     * @param $value
     * @param  array  $attributes
     * @return string
     *
     * @throws UnknownProperties
     */
    final public function set($model, string $key, $value, array $attributes): string
    {
        if (isset($model->model->name)) {
            $modelOfModel = (new ("{$model->model->name}"));

            $columns = array_diff(
                Schema::getColumnListing($modelOfModel->getTable()),
                $modelOfModel->getHidden()
            );

            foreach ($columns as $property) {
                $value[$property]['value'] ??= ucfirst(Str::replace('_', ' ', $property)) ?? '';

                $value[$property] = new DisplayOptionColumnItemDto($value[$property]);
            }
        }

        return json_encode($value);
    }
}
