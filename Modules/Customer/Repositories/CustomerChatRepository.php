<?php

namespace Modules\Customer\Repositories;

use App\Repositories\BaseRepository;
use Modules\Customer\Models\CustomerChatMessage;
use Modules\Customer\Repositories\Criteria\CustomerChatRequestCriteria;

class CustomerChatRepository extends BaseRepository
{
    public function model()
    {
        return CustomerChatMessage::class;
    }

    public function boot()
    {
        $this->pushCriteria(CustomerChatRequestCriteria::class);
    }
}
