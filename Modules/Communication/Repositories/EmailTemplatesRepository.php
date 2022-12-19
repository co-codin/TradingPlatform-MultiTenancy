<?php

namespace Modules\Communication\Repositories;

use App\Repositories\BaseRepository;
use Modules\Communication\Models\EmailTemplates;
use Modules\Communication\Repositories\Criteria\EmailRequestCriteria;

class EmailTemplatesRepository extends BaseRepository
{
    public function model()
    {
        return EmailTemplates::class;
    }

    public function boot()
    {
        $this->pushCriteria(EmailTemplatesRequestCriteria::class);
    }
}
