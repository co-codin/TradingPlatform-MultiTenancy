<?php

declare(strict_types=1);

namespace App\Dto\Auth;

use App\Dto\BaseDto;

final class PasswordResetDto extends BaseDto
{
    /**
     * @var string $email
     */
    public string $email;

    /**
     * @var string $password
     */
    public string $password;

    /**
     * @var string $password_confirmation
     */
    public string $password_confirmation;

    /**
     * @var string $token
     */
    public string $token;
}
