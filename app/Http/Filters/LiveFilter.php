<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Spatie\QueryBuilder\Filters\Filter;

class LiveFilter implements Filter
{
    public function __construct(
        protected array $columns
    ) {}

    public function __invoke(Builder $query, $value, string $property)
    {
        $query->where(function($query) use ($value) {
            foreach (Arr::wrap($this->columns) as $column => $operator) {
                $query->orWhere($column, $operator, $operator === "like" ? "%$value%": $value);
            }
        });
    }
}
