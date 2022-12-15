<?php

declare(strict_types=1);

namespace Modules\Role\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Column;

final class ColumnPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_COLUMNS = 'create columns';

    /**
     * @var string
     */
    public const VIEW_COLUMNS = 'view columns';

    /**
     * @var string
     */
    public const EDIT_COLUMNS = 'edit columns';

    /**
     * @var string
     */
    public const DELETE_COLUMNS = 'delete columns';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_COLUMNS => Action::NAMES['create'],
            self::VIEW_COLUMNS => Action::NAMES['view'],
            self::EDIT_COLUMNS => Action::NAMES['edit'],
            self::DELETE_COLUMNS => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Column::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_COLUMNS => 'Create columns',
            self::VIEW_COLUMNS => 'View columns',
            self::EDIT_COLUMNS => 'Update columns',
            self::DELETE_COLUMNS => 'Delete columns',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Roles';
    }
}
