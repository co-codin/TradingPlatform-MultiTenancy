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
    public const EXPORT_TRANSACTIONS = 'export users';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
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
