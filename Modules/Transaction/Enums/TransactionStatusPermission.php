<?php

declare(strict_types=1);

namespace Modules\Transaction\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Transaction\Models\TransactionStatus;

final class TransactionStatusPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_TRANSACTION_STATUSES = 'create transaction statuses';

    /**
     * @var string
     */
    public const VIEW_TRANSACTION_STATUSES = 'view transaction statuses';

    /**
     * @var string
     */
    public const EDIT_TRANSACTION_STATUSES = 'edit transaction statuses';

    /**
     * @var string
     */
    public const DELETE_TRANSACTION_STATUSES = 'delete transaction statuses';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_TRANSACTION_STATUSES => Action::NAMES['create'],
            self::VIEW_TRANSACTION_STATUSES => Action::NAMES['view'],
            self::EDIT_TRANSACTION_STATUSES => Action::NAMES['edit'],
            self::DELETE_TRANSACTION_STATUSES => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return TransactionStatus::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_TRANSACTION_STATUSES => 'Create transaction statuses',
            self::VIEW_TRANSACTION_STATUSES => 'View transaction statuses',
            self::EDIT_TRANSACTION_STATUSES => 'Edit transaction statuses',
            self::DELETE_TRANSACTION_STATUSES => 'Delete transaction statuses',
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
