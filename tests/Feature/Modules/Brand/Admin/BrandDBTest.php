<?php
declare(strict_types=1);

namespace Tests\Feature\Modules\Brand\Admin;

use Illuminate\Support\Facades\DB;
use Modules\Brand\Jobs\CreateBrandDBJob;
use Tests\TestCase;

class BrandDBTest extends TestCase
{
    public function test_db_create_job()
    {
        CreateBrandDBJob::dispatchNow('brand');

        $this->assertNotNull(DB::selectOne("SELECT 1 FROM pg_database WHERE datname = ?", ['brand']));
    }
}
