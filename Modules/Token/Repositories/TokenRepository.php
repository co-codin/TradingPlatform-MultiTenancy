<?php

namespace Modules\Token\Repositories;

use App\Repositories\BaseRepository;
use Modules\Token\Models\Token;
use Modules\Token\Repositories\Criteria\TokenRequestCriteria;

class TokenRepository extends BaseRepository
{
    public function model()
    {
        return Token::class;
    }

    public function boot()
    {
        $this->pushCriteria(TokenRequestCriteria::class);
    }
}
