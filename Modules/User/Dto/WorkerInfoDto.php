<?php

declare(strict_types=1);

namespace Modules\User\Dto;

use App\Dto\BaseDto;

final class WorkerInfoDto extends BaseDto
{
    /**
     * @var int|null
     */
    public ?int $user_id;

    /**
     * @var string|null
     */
    public ?string $first_name;

    /**
     * @var string|null
     */
    public ?string $last_name;

    /**
     * @var string|null
     */
    public ?string $email;
}
