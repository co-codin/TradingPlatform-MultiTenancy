<?php

declare(strict_types=1);

namespace Modules\Sale\Enums;

use App\Enums\BaseEnum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Action;
use Modules\Sale\Models\SaleStatus;

final class SaleStatusPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_SALE_STATUSES = 'create sale statuses';

    /**
     * @var string
     */
    const VIEW_SALE_STATUSES = 'view sale statuses';

    /**
     * @var string
     */
    const EDIT_SALE_STATUSES = 'edit sale statuses';

    /**
     * @var string
     */
    const DELETE_SALE_STATUSES = 'delete sale statuses';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_SALE_STATUSES => Action::NAMES['create'],
            self::VIEW_SALE_STATUSES => Action::NAMES['view'],
            self::EDIT_SALE_STATUSES => Action::NAMES['edit'],
            self::DELETE_SALE_STATUSES => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return SaleStatus::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Sales';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_SALE_STATUSES => 'Create sale statuses',
            self::VIEW_SALE_STATUSES => 'View sale statuses',
            self::EDIT_SALE_STATUSES => 'Edit sale statuses',
            self::DELETE_SALE_STATUSES => 'Delete sale statuses',
        ];
    }
}
