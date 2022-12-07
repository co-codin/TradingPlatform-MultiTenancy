<?php

namespace Modules\Role\Contracts;

interface PermissionEnum
{
    /**
     * Plural target module name.
     *
     * @return string
     */
    public static function module(): string;

    /**
     * Target model name.
     *
     * @return string
     */
    public static function model(): string;

    /**
     * Actions of enums.
     *
     * @return array
     */
    public static function actions(): array;

    /**
     * Descriptions of enums.
     *
     * @return array
     */
    public static function descriptions(): array;
}
