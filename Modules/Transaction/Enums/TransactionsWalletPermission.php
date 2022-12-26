<?php

declare(strict_types=1);

namespace Modules\Transaction\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Transaction\Models\TransactionsWallet;

final class TransactionsWalletPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_TRANSACTION_WALLET = 'create transaction wallet';

    /**
     * @var string
     */
    public const VIEW_TRANSACTION_WALLET = 'view transaction wallet';

    /**
     * @var string
     */
    public const EDIT_TRANSACTION_WALLET = 'edit transaction wallet';

    /**
     * @var string
     */
    public const DELETE_TRANSACTION_WALLET = 'delete transaction wallet';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_TRANSACTION_WALLET => Action::NAMES['create'],
            self::VIEW_TRANSACTION_WALLET => Action::NAMES['view'],
            self::EDIT_TRANSACTION_WALLET => Action::NAMES['edit'],
            self::DELETE_TRANSACTION_WALLET => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return TransactionsWallet::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_TRANSACTION_WALLET => 'Create transaction wallet',
            self::VIEW_TRANSACTION_WALLET => 'View transaction wallet',
            self::EDIT_TRANSACTION_WALLET => 'Edit transaction wallet',
            self::DELETE_TRANSACTION_WALLET => 'Delete transaction wallet',
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
