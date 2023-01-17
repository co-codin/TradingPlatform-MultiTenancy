<?php

declare(strict_types=1);

namespace Modules\Campaign\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Campaign\Models\CampaignTransaction;
use Modules\Role\Contracts\PermissionEnum;

final class CampaignTransactionPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_CAMPAIGN_TRANSACTION = 'create campaign transaction';

    /**
     * @var string
     */
    public const VIEW_CAMPAIGN_TRANSACTION = 'view campaign transaction';

    /**
     * @var string
     */
    public const EDIT_CAMPAIGN_TRANSACTION = 'edit campaign transaction';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_CAMPAIGN_TRANSACTION => Action::NAMES['create'],
            self::VIEW_CAMPAIGN_TRANSACTION => Action::NAMES['view'],
            self::EDIT_CAMPAIGN_TRANSACTION => Action::NAMES['edit'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return CampaignTransaction::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_CAMPAIGN_TRANSACTION => 'Create campaign transaction',
            self::VIEW_CAMPAIGN_TRANSACTION => 'View campaign transaction',
            self::EDIT_CAMPAIGN_TRANSACTION => 'Edit campaign transaction',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Campaign';
    }
}
