<?php

declare(strict_types=1);

namespace Modules\Brand\Enums;

use App\Models\Action;
use BenSampo\Enum\Enum;
use Modules\Brand\Models\Brand;
use Modules\Role\Contracts\PermissionEnum;

final class BrandPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_BRANDS = 'create brands';

    /**
     * @var string
     */
    const VIEW_BRANDS = 'view brands';

    /**
     * @var string
     */
    const EDIT_BRANDS = 'edit brands';

    /**
     * @var string
     */
    const DELETE_BRANDS = 'delete brands';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_BRANDS => Action::NAMES['create'],
            self::VIEW_BRANDS => Action::NAMES['view'],
            self::EDIT_BRANDS => Action::NAMES['edit'],
            self::DELETE_BRANDS => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Brand::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Brands';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_BRANDS => 'Create brands',
            self::VIEW_BRANDS => 'View brands',
            self::EDIT_BRANDS => 'Update brands',
            self::DELETE_BRANDS => 'Delete brands',
        ];
    }
}
