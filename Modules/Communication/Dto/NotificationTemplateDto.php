<?php

declare(strict_types=1);

namespace Modules\Communication\Dto;

use App\Dto\BaseDto;

final class NotificationTemplateDto extends BaseDto
{
    public ?string $subject;
    public ?string $text;
}
