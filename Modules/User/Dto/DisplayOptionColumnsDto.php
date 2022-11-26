<?php

declare(strict_types=1);

namespace Modules\User\Dto;

use App\Dto\BaseDto;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Modules\User\Models\DisplayOption;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class DisplayOptionColumnsDto extends BaseDto implements CastsAttributes
{
    public DisplayOptionColumnItemDto|array $login = [];
    public DisplayOptionColumnItemDto|array $first_name = [];
    public DisplayOptionColumnItemDto|array $last_name = [];
    public DisplayOptionColumnItemDto|array $email = [];
    public DisplayOptionColumnItemDto|array $is_active = [];
    public DisplayOptionColumnItemDto|array $target = [];
    public DisplayOptionColumnItemDto|array $last_login = [];
    public DisplayOptionColumnItemDto|array $banned_at = [];
    public DisplayOptionColumnItemDto|array $created_at = [];
    public DisplayOptionColumnItemDto|array $updated_at = [];
    public DisplayOptionColumnItemDto|array $deleted_at = [];

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
        foreach ($this->getPublicProperties() as $property) {
            $name = $property->getName();

            $value[$name]['value'] ??= DisplayOption::DEFAULT_COLUMN_VALUES[$name] ?? '';

            $this->{$property->getName()} = new DisplayOptionColumnItemDto($value[$name]);
        }

        return json_encode($this->toArray());
    }
}
