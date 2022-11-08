<?php

namespace Modules\Brand\Services;

class BrandDBService
{
    protected array $tables;

    public function createDB()
    {

    }

    public function setTables(array $tables)
    {
        $this->tables = $tables;

        return $this;
    }
}
