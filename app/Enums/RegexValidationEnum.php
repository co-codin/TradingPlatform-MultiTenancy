<?php

namespace App\Enums;

class RegexValidationEnum extends BaseEnum
{
    /**
     * @var string
     */
    const USERNAME = '/^[\p{Alphabetic}0-9]+(?:_[\p{Alphabetic}0-9]+)*$/u';

    /**
     * @var string
     */
    const FIRSTNAME = self::USERNAME;

    /**
     * @var string
     */
    const LASTNAME = self::USERNAME;

    /**
     * @var string
     */
    const PASSWORD = '/(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[\p{Alphabetic}0-9!@#$%^&*]{8,}/';
}
