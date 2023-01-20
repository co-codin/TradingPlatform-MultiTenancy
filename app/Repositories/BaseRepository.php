<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class BaseRepository
 *
 * @method jsonPaginate(int $maxResults = null, int $defaultSize = null)
 */
abstract class BaseRepository extends \Prettus\Repository\Eloquent\BaseRepository
{
    /**
     * @var BaseColumnPermissionValidator
     */
    private BaseColumnPermissionValidator $columnPermissionValidator;

    /**
     * Add scopes into model request.
     *
     * @param  array  $scopes
     * @return $this
     */
    public function scopes(array $scopes): BaseRepository
    {
        $this->model->scopes($scopes);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function all($columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();

        if ($this->model instanceof Builder) {
            $results = $this->model->get($columns);
        } elseif ($this->model instanceof QueryBuilder) {
            $results = $this->model->get($columns);
        } else {
            $results = $this->model->all($columns);
        }

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }

    /**
     * {@inheritDoc}
     */
    public function paginate($limit = null, $page = null, $columns = ['*'], $method = 'paginate')
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? config('repository.pagination.limit', 15) : $limit;
        $results = $this->model->{$method}($limit, $columns, 'page', $page);
        $results->appends(app('request')->query());
        $this->resetModel();

        return $this->parserResult($results);
    }

    /**
     * Push column permission validator
     *
     * @param  string  $permissionColumnValidator
     * @return BaseRepository
     */
    public function pushPermissionColumnValidator(string $permissionColumnValidator): BaseRepository
    {
        $this->columnPermissionValidator = new $permissionColumnValidator;

        return $this;
    }
}
