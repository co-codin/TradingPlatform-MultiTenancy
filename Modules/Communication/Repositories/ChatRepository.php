<?php

namespace Modules\Communication\Repositories;

use App\Repositories\BaseRepository;
use Modules\Communication\Models\ChatMessage;
use Modules\Communication\Repositories\Criteria\ChatRequestCriteria;

class ChatRepository extends BaseRepository
{
    public function model()
    {
        return ChatMessage::class;
    }

    public function boot()
    {
        $this->pushCriteria(ChatRequestCriteria::class);
    }
}
