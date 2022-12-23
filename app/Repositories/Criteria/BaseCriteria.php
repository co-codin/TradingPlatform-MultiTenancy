<?php

declare(strict_types=1);

namespace App\Repositories\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;

abstract class BaseCriteria implements CriteriaInterface
{
    /**
     * Allowed model fields value.
     *
     * @var array
     */
    protected static array $allowedModelFields = [];

    /**
     * Allowed model fields.
     *
     * @param  string|null  $prefix
     * @param  bool  $nested
     * @return array
     */
    final protected static function allowedModelFields(?string $prefix = null, bool $nested = false): array
    {
        if (! $prefix) {
            return static::$allowedModelFields;
        }

        if ($nested) {
            return array_map(static fn ($field) => "{$prefix}.*.{$field}", static::$allowedModelFields);
        }

        return array_map(static fn ($field) => "{$prefix}.{$field}", static::$allowedModelFields);
    }
}
