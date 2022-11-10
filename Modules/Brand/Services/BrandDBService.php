<?php

namespace Modules\Brand\Services;

use Modules\Brand\Jobs\MigrateBrandDBJob;

class BrandDBService
{
    protected array $tables;
//migrate --path=Modules/Brand/DB/Migrations/create_user_desk_table.php
    public function createDB()
    {

    }

    public function migrateDB(): void
    {
        MigrateBrandDBJob::dispatch($this->tables);
    }

    public function setTables(array $tables): self
    {
        $this->tables = $tables;

        return $this;
    }
}
