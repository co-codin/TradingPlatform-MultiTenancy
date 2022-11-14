<?php
declare(strict_types=1);

namespace Tests\Feature\Modules\Brand\Admin;

use Illuminate\Support\Facades\DB;
use Modules\Brand\Enums\AllowedDBTables;
use Modules\Brand\Jobs\CreateBrandDBJob;
use Modules\Brand\Jobs\MigrateBrandDBJob;
use Tests\TestCase;

class BrandDBTest extends TestCase
{
    public function test_db_create_job()
    {
        CreateBrandDBJob::dispatchNow('brand');

        $this->assertNotNull(DB::selectOne("SELECT 1 FROM pg_database WHERE datname = ?", ['brand']));
    }

    /**
     * @depends test_db_create_job
     *
     * @return void
     */
    public function test_db_migrate_job()
    {

        MigrateBrandDBJob::dispatchNow('brand', array_values(AllowedDBTables::asArray()));

        foreach (array_values(AllowedDBTables::asArray()) as $table) {
            $this->assertTrue(DB::table($table)->exists());
        }
    }
}
