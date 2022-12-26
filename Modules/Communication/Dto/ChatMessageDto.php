<?php

declare(strict_types=1);

namespace Modules\Communication\Dto;

use App\Dto\BaseDto;

final class ChatMessageDto extends BaseDto
{
    public int $customer_id;
    public string $message;
}
