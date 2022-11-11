<?php

namespace Modules\Role\Contracts;

interface PermissionEnum
{
    /**
     * Plural target module name.
     *
     * @return string
     */
    public static function module (): string;

    /**
     * Descriptions of enums.
     *
     * @return array
     */
    public static function descriptions (): array;
}
