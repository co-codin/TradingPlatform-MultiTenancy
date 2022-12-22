<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;
use Spatie\Multitenancy\Models\Tenant;

abstract class BaseFactory extends Factory
{
    /**
     * {@inheritDoc}
     */
    public function create($attributes = [], ?Model $parent = null)
    {
        $tenant = Tenant::current();

        if (! empty($attributes)) {
            return $this->state($attributes)->create([], $parent);
        }

        $results = $this->make($attributes, $parent);

        $tenant->makeCurrent();

        if ($results instanceof Model) {
            $this->store(collect([$results]));

            $this->callAfterCreating(collect([$results]), $parent);
        } else {
            $this->store($results);

            $this->callAfterCreating($results, $parent);
        }

        $tenant->makeCurrent();

        return $results;
    }

    /**
     * {@inheritDoc}
     */
    public function make($attributes = [], ?Model $parent = null)
    {
        $tenant = Tenant::current();
        if (! empty($attributes)) {
            return $this->state($attributes)->make([], $parent);
        }

        if ($this->count === null) {
            return tap($this->makeInstance($parent), function ($instance) {
                $this->callAfterMaking(collect([$instance]));
            });
        }

        if ($this->count < 1) {
            return $this->newModel()->newCollection();
        }

        $instances = $this->newModel()->newCollection(array_map(function () use ($parent, $tenant) {
            $tenant->makeCurrent();
            return $this->makeInstance($parent);
        }, range(1, $this->count)));

        $this->callAfterMaking($instances);

        return $instances;
    }

    /**
     * {@inheritDoc}
     */
    abstract public function definition(): array;
}
