<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use Modules\Communication\Dto\CommentDto;
use Modules\Communication\Models\Comment;
use Modules\Media\Services\AttachmentStorage;

final class CommentStorage
{
    /**
     * Store.
     *
     * @param  CommentDto  $dto
     * @return Comment
     */
    final public function store(CommentDto $dto): Comment
    {
        /** @var Comment $comment */
        if (! $comment = Comment::query()->create($dto->toArray())) {
            throw new \LogicException(__('Can not store comment.'));
        }

        return $comment;
    }

    /**
     * Update.
     *
     * @param  Comment  $comment
     * @param  CommentDto  $commentDto
     * @return Comment
     */
    final public function update(Comment $comment, CommentDto $commentDto): Comment
    {
        if (! $comment->update($commentDto->toArray())) {
            throw new \LogicException(__('Can not update comment.'));
        }

        return $comment;
    }

    /**
     * Destroy.
     *
     * @param  Comment  $comment
     * @return void
     */
    final public function delete(Comment $comment): void
    {
        if (! $comment->delete()) {
            throw new \LogicException(__('Can not delete comment'));
        }
    }
}
