<?php

declare(strict_types=1);

namespace Modules\Customer\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Customer\Models\CustomerChatMessage;
use Modules\Role\Contracts\PermissionEnum;

final class CustomerChatPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_CUSTOMERS_CHAT = 'create customer chat';
    /**
     * @var string
     */
    public const VIEW_CUSTOMERS_CHAT = 'view customer chat';
    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_CUSTOMERS_CHAT => Action::NAMES['create'],
            self::VIEW_CUSTOMERS_CHAT => Action::NAMES['view'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return CustomerChatMessage::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Customer';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_CUSTOMERS_CHAT => 'Create customer chat',
            self::VIEW_CUSTOMERS_CHAT => 'View customer chat',
        ];
    }
}
