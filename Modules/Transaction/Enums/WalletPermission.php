<?php

declare(strict_types=1);

namespace Modules\Transaction\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Transaction\Models\Wallet;

final class WalletPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_WALLET = 'create wallet';

    /**
     * @var string
     */
    public const VIEW_WALLET = 'view wallet';

    /**
     * @var string
     */
    public const EDIT_WALLET = 'edit wallet';

    /**
     * @var string
     */
    public const DELETE_WALLET = 'delete wallet';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_WALLET => Action::NAMES['create'],
            self::VIEW_WALLET => Action::NAMES['view'],
            self::EDIT_WALLET => Action::NAMES['edit'],
            self::DELETE_WALLET => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Wallet::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_WALLET => 'Create wallet',
            self::VIEW_WALLET => 'View wallet',
            self::EDIT_WALLET => 'Edit wallet',
            self::DELETE_WALLET => 'Delete wallet',
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
