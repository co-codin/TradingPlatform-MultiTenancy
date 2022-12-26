<?php

declare(strict_types=1);

namespace Modules\Communication\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Communication\Models\ChatMessage;
use Modules\Role\Contracts\PermissionEnum;

final class ChatPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_COMMUNICATION_CHAT = 'create сommunication chat';
    /**
     * @var string
     */
    public const VIEW_COMMUNICATION_CHAT = 'view сommunication chat';
    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_COMMUNICATION_CHAT => Action::NAMES['create'],
            self::VIEW_COMMUNICATION_CHAT => Action::NAMES['view'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return ChatMessage::class;
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
            self::CREATE_COMMUNICATION_CHAT => 'Create communication chat',
            self::VIEW_COMMUNICATION_CHAT => 'View communication chat',
        ];
    }
}
