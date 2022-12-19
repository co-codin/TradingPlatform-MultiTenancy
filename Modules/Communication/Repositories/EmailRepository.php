<?php

namespace Modules\Communication\Repositories;

use App\Repositories\BaseRepository;
use Modules\Communication\Models\Email;
use Modules\Communication\Repositories\Criteria\EmailRequestCriteria;

class EmailRepository extends BaseRepository
{
    public function model()
    {
        return Email::class;
    }

    public function boot()
    {
        $this->pushCriteria(EmailRequestCriteria::class);
    }
}
