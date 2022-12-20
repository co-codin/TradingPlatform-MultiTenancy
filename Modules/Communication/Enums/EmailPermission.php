<?php

declare(strict_types=1);

namespace Modules\Communication\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Communication\Models\Email;

final class EmailPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_COMMUNICATION_EMAIL = 'create сommunication email';

    /**
     * @var string
     */
    public const VIEW_COMMUNICATION_EMAIL = 'view сommunication email';

    /**
     * @var string
     */
    public const EDIT_COMMUNICATION_EMAIL = 'edit сommunication email';

    /**
     * @var string
     */
    public const DELETE_COMMUNICATION_EMAIL = 'delete сommunication email';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_COMMUNICATION_EMAIL => Action::NAMES['create'],
            self::VIEW_COMMUNICATION_EMAIL => Action::NAMES['view'],
            self::EDIT_COMMUNICATION_EMAIL => Action::NAMES['edit'],
            self::DELETE_COMMUNICATION_EMAIL => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Email::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Communication';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_COMMUNICATION_EMAIL => 'Create communication email',
            self::VIEW_COMMUNICATION_EMAIL => 'View communication email',
            self::EDIT_COMMUNICATION_EMAIL => 'Edit communication email',
            self::DELETE_COMMUNICATION_EMAIL => 'Delete communication email',
        ];
    }
}
