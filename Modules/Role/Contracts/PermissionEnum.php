<?php

namespace Modules\Role\Contracts;

interface PermissionEnum
{
    public static function module (): string;

    public static function descriptions (): array;
}
