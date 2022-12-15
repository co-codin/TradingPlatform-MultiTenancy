<?php

declare(strict_types=1);

namespace Modules\CommunicationProvider\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\CommunicationProvider\Models\CommunicationProvider;
use Modules\Role\Contracts\PermissionEnum;

final class CommunicationProviderPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_TELEPHONY_PROVIDER = 'create communication provider';

    /**
     * @var string
     */
    public const VIEW_TELEPHONY_PROVIDER = 'view communication provider';

    /**
     * @var string
     */
    public const EDIT_TELEPHONY_PROVIDER = 'edit communication provider';

    /**
     * @var string
     */
    public const DELETE_TELEPHONY_PROVIDER = 'delete communication provider';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_TELEPHONY_PROVIDER => Action::NAMES['create'],
            self::VIEW_TELEPHONY_PROVIDER => Action::NAMES['view'],
            self::EDIT_TELEPHONY_PROVIDER => Action::NAMES['edit'],
            self::DELETE_TELEPHONY_PROVIDER => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return CommunicationProvider::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'CommunicationProvider';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_TELEPHONY_PROVIDER => 'Create communication provider',
            self::VIEW_TELEPHONY_PROVIDER => 'View communication provider',
            self::EDIT_TELEPHONY_PROVIDER => 'Edit communication provider',
            self::DELETE_TELEPHONY_PROVIDER => 'Delete communication provider',
        ];
    }
}
