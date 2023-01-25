<?php

declare(strict_types=1);

namespace Modules\Role\Enums;

use App\Models\Action;
use App\Models\Model;
use BenSampo\Enum\Enum;
use Modules\Role\Contracts\PermissionEnum;

final class ModelPermission extends Enum implements PermissionEnum
{
    public const VIEW_MODELS = 'view models';

    public static function actions(): array
    {
        return [
            self::VIEW_MODELS => Action::NAMES['view'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Model::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::VIEW_MODELS => 'View models',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Roles';
    }
}
