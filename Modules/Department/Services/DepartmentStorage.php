<?php

namespace Modules\Department\Services;

use LogicException;
use Modules\Department\Dto\DepartmentDto;
use Modules\Department\Models\Department;

class DepartmentStorage
{
    /**
     * Store department.
     *
     * @param DepartmentDto $dto
     * @return Department
     */
    public function store(DepartmentDto $dto): Department
    {
        $department = Department::create($dto->toArray());

        if (! $department) {
            throw new LogicException(__('Can not create department'));
        }

        return $department;
    }

    /**
     * Update department.
     *
     * @param Department $department
     * @param DepartmentDto $dto
     * @return Department
     * @throws LogicException
     */
    public function update(Department $department, DepartmentDto $dto): Department
    {
        if (! $department->update($dto->toArray())) {
            throw new LogicException(__('Can not update department'));
        }

        return $department;
    }

    /**
     * Delete department.
     *
     * @param Department $department
     * @return bool
     */
    public function delete(Department $department): bool
    {
        if (! $department->delete()) {
            throw new LogicException(__('Can not delete department'));
        }

        return true;
    }
}
