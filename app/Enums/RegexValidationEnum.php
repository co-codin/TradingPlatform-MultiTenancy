<?php

declare(strict_types=1);

namespace App\Enums;

final class RegexValidationEnum extends BaseEnum
{
    /**
     * @var string
     */
    public const USERNAME = '/^[A-Za-z\d_]+$/';

    /**
     * @var string
     */
    public const NAME = "/^[\p{L} ,.'-]+/u";

    /**
     * @var string
     */
    public const PASSWORD = "/^(?!.* )(?=.*[A-Z])(?=.*\d)(?=.*[!\"#$%&'()*+,.\\\/:;<=>?@\[\]^_`{|}~-])[A-Za-z\d!\"#$%&'()*+,.\\\/:;<=>?@\[\]^_`{|}~-]{8,}$/";
}
