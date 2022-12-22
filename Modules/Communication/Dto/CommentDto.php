<?php

declare(strict_types=1);

namespace Modules\Communication\Dto;

use App\Dto\BaseDto;

final class CommentDto extends BaseDto
{
    /**
     * @var ?int
     */
    public ?int $user_id = null;

    /**
     * @var ?int
     */
    public ?int $customer_id = null;

    /**
     * @var ?string
     */
    public ?string $body = null;

    /**
     * @var ?int
     */
    public ?int $position = null;

    /**
     * @var array
     */
    public array $attachments = [];
}
