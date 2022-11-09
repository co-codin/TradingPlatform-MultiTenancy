<?php

namespace Modules\Brand\Services;

class BrandDBService
{
    protected array $tables;
//migrate --path=Modules/Brand/DB/Migrations/create_user_desk_table.php
    public function createDB()
    {

    }

    public function setTables(array $tables)
    {
        $this->tables = $tables;

        return $this;
    }
}
