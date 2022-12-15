<?php

declare(strict_types=1);

namespace Modules\Role\Services;

use Illuminate\Support\Arr;
use LogicException;
use Modules\Role\Dto\ColumnDto;
use Modules\Role\Models\Column;

final class ColumnStorage
{
    /**
     * Store column.
     *
     * @param  ColumnDto  $dto
     * @return Column
     */
    public function store(ColumnDto $dto): Column
    {
        $column = new Column(Arr::only($dto->toArray(),
            ['name']
        ));

        if (! $column->save()) {
            throw new LogicException('Failed to save column');
        }

        return $column;
    }

    /**
     * Update column.
     *
     * @param  Column  $column
     * @param  ColumnDto  $dto
     * @return Column
     */
    public function update(Column $column, ColumnDto $dto): Column
    {
        if (! $column->update(Arr::only($dto->toArray(),
            ['name']
        ))) {
            throw new LogicException('Failed to change column data');
        }

        return $column;
    }

    /**
     * Delete column.
     *
     * @param  Column  $column
     * @return void
     */
    public function delete(Column $column): void
    {
        if (! $column->delete()) {
            throw new LogicException('can not delete role');
        }
    }
}
