<?php declare(strict_types=1);

namespace Modules\Sale\Enums;

use App\Enums\BaseEnum;
use Modules\Role\Contracts\PermissionEnum;

final class SalePermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_SALE = 'create sale';

    /**
     * @var string
     */
    const VIEW_SALE = 'view sale';

    /**
     * @var string
     */
    const EDIT_SALE = 'edit sale';

    /**
     * @var string
     */
    const DELETE_SALE = 'delete sale';

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
            static::CREATE_SALE => 'Create sale',
            static::VIEW_SALE => 'View sale',
            static::EDIT_SALE => 'Edit sale',
            static::DELETE_SALE => 'Delete sale',
        ];
    }
}
