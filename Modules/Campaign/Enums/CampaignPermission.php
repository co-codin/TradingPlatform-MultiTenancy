<?php

declare(strict_types=1);

namespace Modules\Campaign\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Campaign\Models\Campaign;
use Modules\Role\Contracts\PermissionEnum;

final class CampaignPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_CAMPAIGN = 'create campaign';

    /**
     * @var string
     */
    public const VIEW_CAMPAIGN = 'view campaign';

    /**
     * @var string
     */
    public const EDIT_CAMPAIGN = 'edit campaign';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_CAMPAIGN => Action::NAMES['create'],
            self::VIEW_CAMPAIGN => Action::NAMES['view'],
            self::EDIT_CAMPAIGN => Action::NAMES['edit'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Campaign::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_CAMPAIGN => 'Create campaign',
            self::VIEW_CAMPAIGN => 'View campaign',
            self::EDIT_CAMPAIGN => 'Edit campaign',
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
