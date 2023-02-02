<?php

declare(strict_types=1);

namespace Modules\Splitter\Enums;

use BenSampo\Enum\Enum;
use Modules\Customer\Enums\Gender;
use Modules\Department\Enums\DepartmentEnum;

final class SplitterConditions extends Enum
{
    public const COUNTRY = 'Country';

    public const IS_FTD = 'Is FTD';

    public const LANGUAGE = 'Language';

    public const CAMPAIGN = 'Campaign';

    public const CONVERSION_AGENT = 'Conversion Agent';

    public const CONVERSION_MANAGER = 'Conversion Manager';

    public const DESK = 'Desk';

    public const GENDER = 'Gender';

    public const DEPARTMENT = 'Department';

    /**
     * Field list with operators & value types
     *
     * @return array
     */
    public static function fields(): array
    {
        return [
            self::COUNTRY => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array'],
            ],
            self::IS_FTD => [
                'operator' => ['='],
                'valueType' => ['boolean'],
            ],
            self::LANGUAGE => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array'],
            ],
            self::CAMPAIGN => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array'],
            ],
            self::CONVERSION_AGENT => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array'],
            ],
            self::CONVERSION_MANAGER => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array'],
            ],
            self::DESK => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array'],
            ],
            self::GENDER => [
                'operator' => ['='],
                'valueType' => ['string'],
                'possibleValues' => Gender::getKeys(),
            ],
            self::DEPARTMENT => [
                'operator' => ['='],
                'valueType' => ['string'],
                'possibleValues' => DepartmentEnum::getKeys(),
            ],
        ];
    }

    public static function dbFields()
    {
        return [
            self::COUNTRY => 'country_id',
            self::IS_FTD => 'is_ftd',
            self::LANGUAGE => 'language_id',
            self::CAMPAIGN => 'campaign_id',
            self::CONVERSION_AGENT => 'conversion_user_id',
            self::CONVERSION_MANAGER => 'conversion_manager_user_id',
            self::DESK => 'desk_id',
        ];
    }
}
