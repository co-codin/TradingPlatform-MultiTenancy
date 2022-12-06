<?php

namespace Modules\Communication\Repositories;

use App\Repositories\BaseRepository;
use Modules\Communication\Models\Comment;
use Modules\Communication\Repositories\Criteria\CommentRequestCriteria;

class CommentRepository extends BaseRepository
{
    public function model()
    {
        return Comment::class;
    }

    public function boot()
    {
        $this->pushCriteria(CommentRequestCriteria::class);
    }
}
