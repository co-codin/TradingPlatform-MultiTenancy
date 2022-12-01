<?php

declare(strict_types=1);

namespace Modules\Sale\Enums;

use App\Enums\BaseEnum;
use Modules\Role\Contracts\PermissionEnum;

final class SaleStatusPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_SALE_STATUSES = 'create salestatus';

    /**
     * @var string
     */
    const VIEW_SALE_STATUSES = 'view salestatus';

    /**
     * @var string
     */
    const EDIT_SALE_STATUSES = 'edit salestatus';

    /**
     * @var string
     */
    const DELETE_SALE_STATUSES = 'delete salestatus';

    /**
     * @inheritDoc
     */
    public static function module(): string
    {
        return 'Sale';
    }

    /**
     * @inheritDoc
     */
    public static function descriptions(): array
    {
        return [
            static::CREATE_SALE_STATUSES => 'Create salestatus',
            static::VIEW_SALE_STATUSES => 'View salestatus',
            static::EDIT_SALE_STATUSES => 'Edit salestatus',
            static::DELETE_SALE_STATUSES => 'Delete salestatus',
        ];
    }
}
