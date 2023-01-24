<?php

namespace App\Contracts\Models;

interface HasAttributeColumns
{
    /**
     * Get attribute columns array.
     *
     * @return array
     */
    public static function getAttributeColumns(): array;
}
