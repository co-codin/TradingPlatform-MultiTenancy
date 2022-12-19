<?php

declare(strict_types=1);

namespace Modules\Communication\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Communication\Models\CommunicationProvider;
use Modules\Role\Contracts\PermissionEnum;

final class CommunicationProviderPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_COMMUNICATION_PROVIDER = 'create communication provider';

    /**
     * @var string
     */
    public const VIEW_COMMUNICATION_PROVIDER = 'view communication provider';

    /**
     * @var string
     */
    public const EDIT_COMMUNICATION_PROVIDER = 'edit communication provider';

    /**
     * @var string
     */
    public const DELETE_COMMUNICATION_PROVIDER = 'delete communication provider';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_COMMUNICATION_PROVIDER => Action::NAMES['create'],
            self::VIEW_COMMUNICATION_PROVIDER => Action::NAMES['view'],
            self::EDIT_COMMUNICATION_PROVIDER => Action::NAMES['edit'],
            self::DELETE_COMMUNICATION_PROVIDER => Action::NAMES['delete'],
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
        return 'Communication';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_COMMUNICATION_PROVIDER => 'Create communication provider',
            self::VIEW_COMMUNICATION_PROVIDER => 'View communication provider',
            self::EDIT_COMMUNICATION_PROVIDER => 'Edit communication provider',
            self::DELETE_COMMUNICATION_PROVIDER => 'Delete communication provider',
        ];
    }
}
