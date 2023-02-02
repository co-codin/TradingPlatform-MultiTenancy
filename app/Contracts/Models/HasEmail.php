<?php

declare(strict_types=1);

namespace App\Contracts\Models;

interface HasEmail
{
    /**
     * Get first name.
     *
     * @return string
     */
    public function getFirstName(): string;

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail(): string;
}
