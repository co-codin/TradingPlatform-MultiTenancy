<?php

declare(strict_types=1);

namespace Modules\Transaction\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Transaction\Models\TransactionsMt5Type;

final class TransactionsMt5TypePermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_TRANSACTION_MT5_TYPE = 'create transaction Mt5 type';

    /**
     * @var string
     */
    public const VIEW_TRANSACTION_MT5_TYPE = 'view transaction Mt5 type';

    /**
     * @var string
     */
    public const EDIT_TRANSACTION_MT5_TYPE = 'edit transaction Mt5 type';

    /**
     * @var string
     */
    public const DELETE_TRANSACTION_MT5_TYPE = 'delete transaction Mt5 type';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_TRANSACTION_MT5_TYPE => Action::NAMES['create'],
            self::VIEW_TRANSACTION_MT5_TYPE => Action::NAMES['view'],
            self::EDIT_TRANSACTION_MT5_TYPE => Action::NAMES['edit'],
            self::DELETE_TRANSACTION_MT5_TYPE => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return TransactionsMt5Type::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_TRANSACTION_MT5_TYPE => 'Create transaction Mt5 type',
            self::VIEW_TRANSACTION_MT5_TYPE => 'View transaction Mt5 type',
            self::EDIT_TRANSACTION_MT5_TYPE => 'Edit transaction Mt5 type',
            self::DELETE_TRANSACTION_MT5_TYPE => 'Delete transaction Mt5 type',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Transaction';
    }
}
