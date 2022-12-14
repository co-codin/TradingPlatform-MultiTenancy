<?php

namespace App\Relationships\Traits;

use Spatie\Multitenancy\Models\Tenant;

trait WhereHasForTenant
{
    /**
     * Initialize WhereHasForTenant
     *
     * @return void
     */
    public function initializeWhereHasForTenant()
    {
        if ($this->table && ! str_contains($this->table, 'public.') && $tenant = Tenant::current()) {
            $this->table = $tenant->getDatabaseName() . '.' . $this->getTable();
        }
    }

    /**
     * Define a many-to-many relationship.
     *
     * @param  string  $related
     * @param  string|null  $table
     * @param  string|null  $foreignPivotKey
     * @param  string|null  $relatedPivotKey
     * @param  string|null  $parentKey
     * @param  string|null  $relatedKey
     * @param  string|null  $relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function belongsToManyTenant(
        $related,
        $table = null,
        $foreignPivotKey = null,
        $relatedPivotKey = null,
        $parentKey = null,
        $relatedKey = null,
        $relation = null
    ) {
        $table = (Tenant::current() ? Tenant::current()->getDatabaseName() . '.' : '') . $table;

        return $this->belongsToMany(
            $related,
            $table,
            $foreignPivotKey,
            $relatedPivotKey,
            $parentKey,
            $relatedKey,
            $relation
        );
    }
}
