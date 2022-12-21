<?php

declare(strict_types=1);

namespace Modules\Communication\Dto;

use App\Dto\BaseDto;

final class EmailDto extends BaseDto
{
    public ?int $email_template_id;

    public ?string $subject;

    public ?string $body;

    public ?bool $sent_by_system;

    public ?int $user_id;
}
