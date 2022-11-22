<?php

declare(strict_types=1);

namespace Modules\User\Dto;

use App\Dto\BaseDto;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class DisplayOptionSettingsDto extends BaseDto implements CastsAttributes
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
    final public function get($model, string $key, $value, array $attributes): array
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
    final public function set($model, string $key, $value, array $attributes): string
    {
        return json_encode((new self($value))->toArray());
    }
}
