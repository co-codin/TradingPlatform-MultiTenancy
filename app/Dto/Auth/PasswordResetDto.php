<?php

declare(strict_types=1);

namespace App\Dto\Auth;

use App\Dto\BaseDto;

final class PasswordResetDto extends BaseDto
{
    /**
     * @var string
     */
    public string $email;

    /**
     * @var string
     */
    public string $password;

    /**
     * @var string
     */
    public string $token;
}
