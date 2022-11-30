<?php declare(strict_types=1);

namespace Modules\Sale\Enums;

use App\Enums\BaseEnum;
use Modules\Role\Contracts\PermissionEnum;

final class SaleStatusPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_SALESTATUS = 'create salestatus';

    /**
     * @var string
     */
    const VIEW_SALESTATUS = 'view salestatus';

    /**
     * @var string
     */
    const EDIT_SALESTATUS = 'edit salestatus';

    /**
     * @var string
     */
    const DELETE_SALESTATUS = 'delete salestatus';

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
            static::CREATE_SALESTATUS => 'Create salestatus',
            static::VIEW_SALESTATUS => 'View salestatus',
            static::EDIT_SALESTATUS => 'Edit salestatus',
            static::DELETE_SALESTATUS => 'Delete salestatus',
        ];
    }
}
