<?php

namespace App\Repositories\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

abstract class BaseCriteria implements CriteriaInterface
{
    /**
     * @var array
     */
    protected array $allowedModelFields = [];

    /**
     * @inheritDoc
     */
    abstract public function apply($model, RepositoryInterface $repository);

    /**
     * Allowed model fields.
     *
     * @param string|null $prefix
     * @return array
     */
    protected function allowedModelFields(string $prefix = null): array
    {
        if (! $prefix) {
            return $this->allowedModelFields;
        }

        return array_map(fn($field) => "{$prefix}.{$field}", $this->allowedModelFields);
    }
}
