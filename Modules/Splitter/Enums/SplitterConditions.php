<?php

declare(strict_types=1);

namespace Modules\Splitter\Enums;

use BenSampo\Enum\Enum;

final class SplitterConditions extends Enum
{
    /**
     * Field list with operators & value types
     *
     * @return array
     */
    public static function fields(): array
    {
        return [
            'Country' => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array']
            ],

            'Is FTD' => [
                'operator' => ['='],
                'valueType' => ['boolean']
            ],

            'Language' => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array']
            ],

            'Campaign' => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array']
            ],

            'Conversion Agent' => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array']
            ],

            'Conversion Manager' => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array']
            ],

            'Desk' => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array']
            ],

            'Gender' => [
                'operator' => ['='],
                'valueType' => ['string']
            ],

            'Department' => [
                'operator' => ['IN', 'NOT IN'],
                'valueType' => ['array']
            ],
        ];
    }
}
