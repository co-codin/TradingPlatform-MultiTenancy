<?php

namespace Modules\Token\Services;

use Modules\Token\Dto\TokenDto;
use Modules\Token\Models\Token;

class TokenStorage
{
    public function store(TokenDto $tokenDto)
    {
        return Token::query()->create($tokenDto->toArray());
    }

    public function update(Token $token, TokenDto $tokenDto)
    {
        $attributes = $tokenDto->toArray();

        if (!$token->update($attributes)) {
            throw new \LogicException('can not update token');
        }

        return $token;
    }

    public function delete(Token $token)
    {
        if (!$token->delete()) {
            throw new \LogicException('can not delete token');
        }
    }
}
