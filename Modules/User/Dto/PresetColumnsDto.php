<?php

declare(strict_types=1);

namespace Modules\User\Dto;

use App\Dto\BaseDto;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

final class PresetColumnsDto extends BaseDto implements CastsAttributes
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
     */
    final public function set($model, string $key, $value, array $attributes): string
    {
        return json_encode($value);
    }
}
