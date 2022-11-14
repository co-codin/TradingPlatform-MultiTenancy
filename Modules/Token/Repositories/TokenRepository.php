<?php

namespace Modules\Token\Repositories;

use App\Repositories\BaseRepository;
use Modules\Token\Models\Token;
use Modules\Token\Repositories\Criteria\TokenRequestCriteria;

class TokenRepository extends BaseRepository
{
    /**
     * @inheritDoc
     */
    public function model(): string
    {
        return Token::class;
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->pushCriteria(TokenRequestCriteria::class);
    }
}
