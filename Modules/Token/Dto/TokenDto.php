<?php

namespace Modules\Token\Dto;

use App\Dto\BaseDto;

class TokenDto extends BaseDto
{
    public ?int $user_id;

    public ?string $token;

    public ?string $description;

    public ?string $ip;
}
