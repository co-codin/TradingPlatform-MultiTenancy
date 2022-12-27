<?php

declare(strict_types=1);

namespace Modules\Transaction\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Transaction\Models\TransactionsMethod;

final class TransactionsMethodPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_TRANSACTION_METHOD = 'create transaction method';

    /**
     * @var string
     */
    public const VIEW_TRANSACTION_METHOD = 'view transaction method';

    /**
     * @var string
     */
    public const EDIT_TRANSACTION_METHOD = 'edit transaction method';

    /**
     * @var string
     */
    public const DELETE_TRANSACTION_METHOD = 'delete transaction method';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_TRANSACTION_METHOD => Action::NAMES['create'],
            self::VIEW_TRANSACTION_METHOD => Action::NAMES['view'],
            self::EDIT_TRANSACTION_METHOD => Action::NAMES['edit'],
            self::DELETE_TRANSACTION_METHOD => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return TransactionsMethod::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_TRANSACTION_METHOD => 'Create transaction method',
            self::VIEW_TRANSACTION_METHOD => 'View transaction method',
            self::EDIT_TRANSACTION_METHOD => 'Edit transaction method',
            self::DELETE_TRANSACTION_METHOD => 'Delete transaction method',
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
