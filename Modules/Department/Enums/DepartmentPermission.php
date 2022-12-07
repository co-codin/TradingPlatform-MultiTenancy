<?php

declare(strict_types=1);

namespace Modules\Department\Enums;

use App\Enums\BaseEnum;
use Modules\Department\Models\Department;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Action;

final class DepartmentPermission extends BaseEnum implements PermissionEnum
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
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_DEPARTMENTS => Action::NAMES['create'],
            self::VIEW_DEPARTMENTS => Action::NAMES['view'],
            self::EDIT_DEPARTMENTS => Action::NAMES['edit'],
            self::DELETE_DEPARTMENTS => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Department::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Departments';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_DEPARTMENTS => 'Create departments',
            self::VIEW_DEPARTMENTS => 'View departments',
            self::EDIT_DEPARTMENTS => 'Edit departments',
            self::DELETE_DEPARTMENTS => 'Delete departments',
        ];
    }
}
