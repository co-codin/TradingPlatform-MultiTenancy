<?php

namespace Modules\Token\Repositories;

use App\Repositories\BaseRepository;
use Modules\Token\Models\Token;

class TokenRepository extends BaseRepository
{
    public function model()
    {
        return Token::class;
    }

    public function boot()
    {
        $this->pushCriteria();
    }
}
