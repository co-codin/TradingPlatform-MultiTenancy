<?php

declare(strict_types=1);

namespace Modules\Desk\Enums;

use App\Models\Action;
use Modules\Desk\Models\Desk;
use Modules\Role\Contracts\PermissionEnum;

final class DeskPermission implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_DESKS = 'create desks';

    /**
     * @var string
     */
    const VIEW_DESKS = 'view desks';

    /**
     * @var string
     */
    const EDIT_DESKS = 'edit desks';

    /**
     * @var string
     */
    const DELETE_DESKS = 'delete desks';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_DESKS => Action::NAMES['create'],
            self::VIEW_DESKS => Action::NAMES['view'],
            self::EDIT_DESKS => Action::NAMES['edit'],
            self::DELETE_DESKS => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Desk::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Desks';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_DESKS => 'Create desks',
            self::VIEW_DESKS => 'View desks',
            self::EDIT_DESKS => 'Edit desks',
            self::DELETE_DESKS => 'Delete desks',
        ];
    }
}
