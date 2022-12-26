<?php

declare(strict_types=1);

namespace Modules\Customer\Dto;

use App\Dto\BaseDto;

final class CustomerChatMessageDto extends BaseDto
{
    public string $message;
}
