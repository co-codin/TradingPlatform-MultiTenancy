<?php

declare(strict_types=1);

namespace Modules\Customer\Dto;

use App\Dto\BaseDto;

final class CustomerDto extends BaseDto
{
    /**
     * @var string
     */
    public string $first_name = '';

    /**
     * @var string
     */
    public string $last_name = '';

    /**
     * @var int
     */
    public int $gender = 0;

    /**
     * @var string
     */
    public string $email = '';

    /**
     * @var string
     */
    public string $password = '';

    /**
     * @var string
     */
    public string $phone = '';

    /**
     * @var int
     */
    public int $country_id = 0;

    /**
     * @var ?string
     */
    public ?string $banned_at = null;
}
