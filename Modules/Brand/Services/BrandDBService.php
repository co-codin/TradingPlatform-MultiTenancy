<?php

namespace Modules\Brand\Services;

use Modules\Brand\Jobs\MigrateBrandDBJob;
use Modules\Brand\Models\Brand;

class BrandDBService
{
    protected Brand $brand;

    protected array $tables;
//migrate --path=Modules/Brand/DB/Migrations/create_user_desk_table.php
    public function createDB()
    {

    }

    public function migrateDB(): void
    {
        MigrateBrandDBJob::dispatch($this->tables);
    }

    public function setBrand(Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function setTables(array $tables): self
    {
        $this->tables = $tables;

        return $this;
    }
}
