<?php

declare(strict_types=1);

namespace Modules\Transaction\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Transaction\Models\Transaction;

final class TransactionPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_TRANSACTIONS = 'create transactions';

    /**
     * @var string
     */
    public const VIEW_TRANSACTIONS = 'view transactions';

    /**
     * @var string
     */
    public const EDIT_TRANSACTIONS = 'edit transactions';

    /**
     * @var string
     */
    public const DELETE_TRANSACTIONS = 'delete transactions';

    /**
     * @var string
     */
    public const EXPORT_TRANSACTIONS = 'export users';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_TRANSACTIONS => Action::NAMES['create'],
            self::VIEW_TRANSACTIONS => Action::NAMES['view'],
            self::EDIT_TRANSACTIONS => Action::NAMES['edit'],
            self::DELETE_TRANSACTIONS => Action::NAMES['delete'],
            self::EXPORT_TRANSACTIONS => Action::NAMES['export'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Transaction::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_TRANSACTIONS => 'Create users',
            self::VIEW_TRANSACTIONS => 'View users',
            self::EDIT_TRANSACTIONS => 'Edit users',
            self::DELETE_TRANSACTIONS => 'Delete users',
            self::EXPORT_TRANSACTIONS => 'Export users',
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
