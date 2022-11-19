<?php

namespace Modules\Department\Enums;

use App\Enums\BaseEnum;
use Modules\Role\Contracts\PermissionEnum;

class DepartmentPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_DEPARTMENTS = 'create departments';

    /**
     * @var string
     */
    const VIEW_DEPARTMENTS = 'view departments';

    /**
     * @var string
     */
    const EDIT_DEPARTMENTS = 'edit departments';

    /**
     * @var string
     */
    const DELETE_DEPARTMENTS = 'delete departments';

    /**
     * @inheritDoc
     */
    public static function module(): string
    {
        return 'Departments';
    }

    /**
     * @inheritDoc
     */
    public static function descriptions(): array
    {
        return [
            static::CREATE_DEPARTMENTS => 'Create departments',
            static::VIEW_DEPARTMENTS => 'View departments',
            static::EDIT_DEPARTMENTS => 'Edit departments',
            static::DELETE_DEPARTMENTS => 'Delete departments',
        ];
    }
}
