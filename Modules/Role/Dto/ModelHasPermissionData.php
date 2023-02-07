<?php

declare(strict_types=1);

namespace Modules\Role\Dto;

use App\Dto\BaseDto;
use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class ModelHasPermissionData extends BaseDto implements CastsAttributes
{
    /**
     * @var string|null
     */
    public ?string $reason = null;

    /**
     * @param $model
     * @param  string  $key
     * @param $value
     * @param  array  $attributes
     * @return array
     */
    final public function get($model, string $key, $value, array $attributes): array
    {
        return json_decode($value ?? '[]', true);
    }

    /**
     * @param $model
     * @param  string  $key
     * @param $value
     * @param  array  $attributes
     * @return string
     *
     * @throws UnknownProperties
     * @throws Exception
     */
    final public function set($model, string $key, $value, array $attributes): string
    {
        return json_encode((new self($value))->toArray());
    }
}
