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
    public const PASSWORD = <<<'EOD'
/^(?!.* )(?=.*[A-Z])(?=.*\d)(?=.*[!"#$%&'()*+,.\/\\:;<=>?@\][^_`{|}~-])[A-Za-z\d!"#$%&'()*+,.\/\\:;<=>?@\][^_`{|}~-]{8,}$/
EOD;

    /**
     * @var string
     */
    public const PHONE = "/^(\s*)?(\+)?([- ()]?\d[- ()]?){10,14}(\s*)?$/";
}
