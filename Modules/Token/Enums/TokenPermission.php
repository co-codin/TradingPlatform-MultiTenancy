<?php

declare(strict_types=1);

namespace Modules\Token\Enums;

use Modules\Role\Contracts\PermissionEnum;
use Modules\Role\Models\Action;
use Modules\Token\Models\Token;

final class TokenPermission implements PermissionEnum
{
    /**
     * @var string
     */
    const CREATE_TOKENS = 'create tokens';

    /**
     * @var string
     */
    const VIEW_TOKENS = 'view tokens';

    /**
     * @var string
     */
    const EDIT_TOKENS = 'edit tokens';

    /**
     * @var string
     */
    const DELETE_TOKENS = 'delete tokens';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_TOKENS => Action::NAMES['create'],
            self::VIEW_TOKENS => Action::NAMES['view'],
            self::EDIT_TOKENS => Action::NAMES['edit'],
            self::DELETE_TOKENS => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Token::class;
    }

    /**
     * {@inheritDoc}
     */
    public static function module(): string
    {
        return 'Tokens';
    }

    /**
     * {@inheritDoc}
     */
    public static function descriptions(): array
    {
        return [
            self::CREATE_TOKENS => 'Create tokens',
            self::VIEW_TOKENS => 'View tokens',
            self::EDIT_TOKENS => 'Update tokens',
            self::DELETE_TOKENS => 'Delete tokens',
        ];
    }
}
