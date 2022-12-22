<?php

declare(strict_types=1);

namespace Modules\Communication\Enums;

use App\Enums\BaseEnum;
use App\Models\Action;
use Modules\Communication\Models\Comment;
use Modules\Role\Contracts\PermissionEnum;

final class CommentPermission extends BaseEnum implements PermissionEnum
{
    /**
     * @var string
     */
    public const CREATE_COMMENT = 'create comment';

    /**
     * @var string
     */
    public const VIEW_COMMENT = 'view comment';

    /**
     * @var string
     */
    public const EDIT_COMMENT = 'edit comment';

    /**
     * @var string
     */
    public const DELETE_COMMENT = 'delete comment';

    /**
     * {@inheritDoc}
     */
    public static function actions(): array
    {
        return [
            self::CREATE_COMMENT => Action::NAMES['create'],
            self::VIEW_COMMENT => Action::NAMES['view'],
            self::EDIT_COMMENT => Action::NAMES['edit'],
            self::DELETE_COMMENT => Action::NAMES['delete'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function model(): string
    {
        return Comment::class;
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
            self::CREATE_COMMENT => 'Create comment',
            self::VIEW_COMMENT => 'View comment',
            self::EDIT_COMMENT => 'Edit comment',
            self::DELETE_COMMENT => 'Delete comment',
        ];
    }
}
