<?php

declare(strict_types=1);

namespace Modules\Communication\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Communication\Models\CommunicationExtension;
use Modules\Role\Contracts\PermissionEnum;

final class CommunicationExtensionPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_COMMUNICATION_EXTENSION = 'create communication extension';

    /**
     * @var string
     */
    public const VIEW_COMMUNICATION_EXTENSION = 'view communication extension';

    /**
     * @var string
     */
    public const EDIT_COMMUNICATION_EXTENSION = 'edit communication extension';

    /**
     * @var string
     */
    public const DELETE_COMMUNICATION_EXTENSION = 'delete communication extension';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_COMMUNICATION_EXTENSION => Action::NAMES['create'],
            self::VIEW_COMMUNICATION_EXTENSION => Action::NAMES['view'],
            self::EDIT_COMMUNICATION_EXTENSION => Action::NAMES['edit'],
            self::DELETE_COMMUNICATION_EXTENSION => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return CommunicationExtension::class;
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
            self::CREATE_COMMUNICATION_EXTENSION => 'Create communication extension',
            self::VIEW_COMMUNICATION_EXTENSION => 'View communication extension',
            self::EDIT_COMMUNICATION_EXTENSION => 'Edit communication extension',
            self::DELETE_COMMUNICATION_EXTENSION => 'Delete communication extension',
        ];
    }
}
