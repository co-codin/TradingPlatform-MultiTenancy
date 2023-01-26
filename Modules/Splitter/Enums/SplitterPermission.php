<?php

declare(strict_types=1);

namespace Modules\Splitter\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Splitter\Models\Splitter;

final class SplitterPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_SPLITTER = 'create splitter';

    /**
     * @var string
     */
    public const VIEW_SPLITTER = 'view splitter';

    /**
     * @var string
     */
    public const EDIT_SPLITTER = 'edit splitter';

    /**
     * @var string
     */
    public const EDIT_SPLITTER_POSITIONS = 'edit splitter postitions';

    /**
     * @var string
     */
    public const DELETE_SPLITTER = 'delete splitter';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_SPLITTER => Action::NAMES['create'],
            self::VIEW_SPLITTER => Action::NAMES['view'],
            self::EDIT_SPLITTER => Action::NAMES['edit'],
            self::EDIT_SPLITTER_POSITIONS => Action::NAMES['edit'],
            self::DELETE_SPLITTER => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Splitter::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_SPLITTER => 'Create splitter',
            self::VIEW_SPLITTER => 'View splitter',
            self::EDIT_SPLITTER => 'Edit splitter',
            self::EDIT_SPLITTER_POSITIONS => 'Edit splitter positions',
            self::DELETE_SPLITTER => 'Delete splitter',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Splitter';
    }
}
