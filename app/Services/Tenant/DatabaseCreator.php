<?php

namespace App\Services\Tenant;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseCreator
{
    /**
     * Create DB.
     *
     * @param string $dbName
     * @return bool
     */
    public function create(string $dbName): bool
    {
        return Schema::createDatabase($dbName);
    }

    /**
     * Create schema.
     *
     * @param string $schemaName
     * @return bool
     */
    public function createSchema(string $schemaName): bool
    {
        return DB::unprepared("CREATE SCHEMA {$schemaName}");
    }

    /**
     * Drop schema.
     *
     * @param string $schemaName
     * @return bool
     */
    public function dropSchema(string $schemaName): bool
    {
        return DB::unprepared("DROP SCHEMA IF EXISTS {$schemaName} CASCADE");
    }
}
