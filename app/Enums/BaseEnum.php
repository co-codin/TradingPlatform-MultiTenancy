<?php


namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum as BenSampoEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class Enum
 * @package App\Enums
 */
abstract class BaseEnum extends BenSampoEnum implements LocalizedEnum
{
    protected static ?string $moduleName = null;

    protected static function setModuleName(): void
    {
        static::$moduleName = null;

        $namespaceName = static::class;
        $findMe = "Modules";
        $pos = strpos($namespaceName, $findMe);

        if ($pos !== false) {
            preg_match("~[\\\\]+\\w+[\\\\]~", $namespaceName, $matches);
            static::$moduleName = strtolower(stripslashes($matches[0]));
        }
    }

    /**
     * Get the default localization key.
     *
     * @return string
     */
    public static function getLocalizationKey(): string
    {
        static::setModuleName();

        return !is_null(static::$moduleName)
            ? static::$moduleName . '::' . parent::getLocalizationKey()
            : parent::getLocalizationKey();
    }

    public static function getFilterItemDescription(string $key): ?string
    {
        $key = Str::ucfirst($key);
        $constant = Arr::get(self::getConstants(), $key);

        return self::getDescription($constant);
    }

    public static function getFilterItemValue(string $value): ?string
    {
        return $value;
    }

    public function toArray(): array
    {
        return (array)$this;
    }
}
