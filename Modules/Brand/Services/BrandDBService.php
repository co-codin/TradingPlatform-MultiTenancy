<?php

namespace Modules\Brand\Services;

use Modules\Brand\Jobs\CreateBrandDBJob;
use Modules\Brand\Jobs\MigrateBrandDBJob;
use Modules\Brand\Models\Brand;
use Illuminate\Support\Facades\Bus;

class BrandDBService
{
    protected Brand $brand;

    protected array $tables;

    public function migrateDB(): void
    {
        MigrateBrandDBJob::dispatch($this->brand->slug, $this->tables);
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
