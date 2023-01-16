<?php

declare(strict_types=1);

namespace Modules\Config\Dto;

use App\Dto\BaseDto;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Modules\Config\Models\Config;

final class ConfigValue extends BaseDto implements CastsAttributes
{
    /**
     * @param $model
     * @param  string  $key
     * @param $value
     * @param  array  $attributes
     * @return array|string
     */
    public function get($model, string $key, $value, array $attributes): mixed
    {
        /** @var Config $model */
        return match (true) {
            $model->isJsonDataType() => json_decode($value, true),
            $model->isStringDataType() => $value,
            $model->isIntegerDataType() => (int) $value,
            default => $value,
        };
    }

    /**
     * @param $model
     * @param  string  $key
     * @param $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        /** @var Config $model */
        return match (true) {
            $model->isJsonDataType() => json_encode($value),
            $model->isStringDataType() => $value,
            default => (string) $value,
        };
    }
}
