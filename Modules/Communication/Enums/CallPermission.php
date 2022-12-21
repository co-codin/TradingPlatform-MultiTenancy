<?php

declare(strict_types=1);

namespace Modules\Communication\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Communication\Models\Call;
use Modules\Role\Contracts\PermissionEnum;

final class CallPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_COMMUNICATION_CALL = 'create сommunication call';

    /**
     * @var string
     */
    public const VIEW_COMMUNICATION_CALL = 'view сommunication call';

    /**
     * @var string
     */
    public const EDIT_COMMUNICATION_CALL = 'edit сommunication call';

    /**
     * @var string
     */
    public const DELETE_COMMUNICATION_CALL = 'delete сommunication call';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_COMMUNICATION_CALL => Action::NAMES['create'],
            self::VIEW_COMMUNICATION_CALL => Action::NAMES['view'],
            self::EDIT_COMMUNICATION_CALL => Action::NAMES['edit'],
            self::DELETE_COMMUNICATION_CALL => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Call::class;
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
            self::CREATE_COMMUNICATION_CALL => 'Create communication call',
            self::VIEW_COMMUNICATION_CALL => 'View communication call',
            self::EDIT_COMMUNICATION_CALL => 'Edit communication call',
            self::DELETE_COMMUNICATION_CALL => 'Delete communication call',
        ];
    }
}
