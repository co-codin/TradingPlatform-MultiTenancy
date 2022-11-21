<?php

namespace Modules\User\Dto;

use App\Dto\BaseDto;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class DisplayOptionSettingsDto extends BaseDto implements CastsAttributes
{
    /**
     * @var int $per_page
     */
    public int $per_page = 20;

    /**
     * @param $model
     * @param string $key
     * @param $value
     * @param array $attributes
     * @return array
     */
    public function get($model, string $key, $value, array $attributes): array
    {
        return json_decode($value, true);
    }

    /**
     * @param $model
     * @param string $key
     * @param $value
     * @param array $attributes
     * @return string
     * @throws UnknownProperties
     */
    public function set($model, string $key, $value, array $attributes): string
    {
        return json_encode((new self($value))->toArray());
    }
}
