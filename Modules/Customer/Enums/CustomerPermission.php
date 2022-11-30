<?php

declare(strict_types=1);

namespace Modules\Customer\Enums;

use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;

final class CustomerPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_CUSTOMERS = 'create customers';

    /**
     * @var string
     */
    public const VIEW_CUSTOMERS = 'view customers';

    /**
     * @var string
     */
    public const EDIT_CUSTOMERS = 'edit customers';

    /**
     * @var string
     */
    public const DELETE_CUSTOMERS = 'delete customers';

    /**
     * @var string
     */
    public const BAN_CUSTOMERS = 'ban customers';

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_CUSTOMERS => 'Create customers',
            self::VIEW_CUSTOMERS => 'View customers',
            self::EDIT_CUSTOMERS => 'Edit customers',
            self::DELETE_CUSTOMERS => 'Delete customers',
            self::BAN_CUSTOMERS => 'Ban customers',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Customer';
    }
}
