<?php

declare(strict_types=1);

namespace Modules\Customer\Enums;

use BenSampo\Enum\Enum;
use Modules\Customer\Models\Customer;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Action;

final class CustomerPermission extends Enum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_CUSTOMERS = 'create customers';

    /**
     * @var string
     */
    public const VIEW_CUSTOMERS = 'view customers';

    /**
     * @var string
     */
    public const EDIT_CUSTOMERS = 'edit customers';

    /**
     * @var string
     */
    public const DELETE_CUSTOMERS = 'delete customers';

    /**
     * @var string
     */
    public const IMPERSONATE_CUSTOMERS = 'impersonate customers';

    /**
     * @var string
     */
    public const EXPORT_CUSTOMERS = 'export customers';

    /**
     * @var string
     */
    public const IMPORT_CUSTOMERS = 'import customers';

    /**
     * @var string
     */
    public const BAN_CUSTOMERS = 'ban customers';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_CUSTOMERS => Action::NAMES['create'],
            self::VIEW_CUSTOMERS => Action::NAMES['view'],
            self::EDIT_CUSTOMERS => Action::NAMES['edit'],
            self::DELETE_CUSTOMERS => Action::NAMES['delete'],
            self::IMPERSONATE_CUSTOMERS => Action::NAMES['impersonate'],
            self::EXPORT_CUSTOMERS => Action::NAMES['export'],
            self::IMPORT_CUSTOMERS => Action::NAMES['import'],
            self::BAN_CUSTOMERS => Action::NAMES['ban'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Customer::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_CUSTOMERS => 'Create customers',
            self::VIEW_CUSTOMERS => 'View customers',
            self::EDIT_CUSTOMERS => 'Edit customers',
            self::DELETE_CUSTOMERS => 'Delete customers',
            self::IMPERSONATE_CUSTOMERS => 'Impersonate customers',
            self::EXPORT_CUSTOMERS => 'Export customers',
            self::IMPORT_CUSTOMERS => 'Import customers',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Customer';
    }
}
