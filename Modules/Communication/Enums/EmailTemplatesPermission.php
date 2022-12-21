<?php

declare(strict_types=1);

namespace Modules\Communication\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Role\Contracts\PermissionEnum;
use Modules\Communication\Models\EmailTemplates;

final class EmailTemplatesPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_COMMUNICATION_EMAIL_TEMPLATE = 'create сommunication email template';

    /**
     * @var string
     */
    public const VIEW_COMMUNICATION_EMAIL_TEMPLATE = 'view сommunication email template';

    /**
     * @var string
     */
    public const EDIT_COMMUNICATION_EMAIL_TEMPLATE = 'edit сommunication email template';

    /**
     * @var string
     */
    public const DELETE_COMMUNICATION_EMAIL_TEMPLATE = 'delete сommunication email template';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_COMMUNICATION_EMAIL_TEMPLATE => Action::NAMES['create'],
            self::VIEW_COMMUNICATION_EMAIL_TEMPLATE => Action::NAMES['view'],
            self::EDIT_COMMUNICATION_EMAIL_TEMPLATE => Action::NAMES['edit'],
            self::DELETE_COMMUNICATION_EMAIL_TEMPLATE => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return EmailTemplates::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Communication';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_COMMUNICATION_EMAIL_TEMPLATE => 'Create communication email template',
            self::VIEW_COMMUNICATION_EMAIL_TEMPLATE => 'View communication email template',
            self::EDIT_COMMUNICATION_EMAIL_TEMPLATE => 'Edit communication email template',
            self::DELETE_COMMUNICATION_EMAIL_TEMPLATE => 'Delete communication email template',
        ];
    }
}
