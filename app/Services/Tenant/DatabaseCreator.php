<?php

namespace App\Services\Tenant;

use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseCreator
{
    /**
     * @param string $dbName
     * @return bool
     */
    public function create(string $dbName): bool
    {
        return Schema::createDatabase($dbName);
    }
}

