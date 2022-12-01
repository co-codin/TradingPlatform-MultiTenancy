<?php

declare(strict_types=1);

namespace App\Repositories\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;

abstract class BaseCriteria implements CriteriaInterface
{
    protected static array $allowedModelFields = [];

    /**
     * Allowed model fields.
     *
     * @param  string|null  $prefix
     * @return array
     */
    final protected static function allowedModelFields(?string $prefix): array
    {
        if (! $prefix) {
            return static::$allowedModelFields;
        }

        return array_map(static fn ($field) => "{$prefix}.{$field}", static::$allowedModelFields);
    }
}
