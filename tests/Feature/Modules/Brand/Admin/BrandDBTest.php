<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Brand\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Modules\Brand\Enums\AllowedDBTables;
use Modules\Brand\Enums\BrandPermission;
use Modules\Brand\Jobs\CreateSchemaJob;
use Modules\Brand\Jobs\MigrateStructureJob;
use Modules\Brand\Models\Brand;
use Modules\Brand\Services\BrandDBService;
use Illuminate\Foundation\Testing\TestCase;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Tests\Traits\HasAuth;

final class BrandDBTest extends TestCase
{
    use HasAuth;
    use DatabaseTransactions;

//    public function test_db_create_job()
//    {
//        CreateSchemaJob::dispatchNow('brand');
//
//        $this->assertNotNull(DB::selectOne("SELECT 1 FROM pg_database WHERE datname = ?", ['brand']));
//    }
//
//    /**
//     * @depends test_db_create_job
//     *
//     * @return void
//     */
//    public function test_db_migrate_job()
//    {
//
//        MigrateStructureJob::dispatchNow('brand', array_values(AllowedDBTables::asArray()));
//
//        foreach (array_values(AllowedDBTables::asArray()) as $table) {
//            $this->assertTrue(DB::table($table)->exists());
//        }
//    }

    /**
     * @test
     */
    public function test_import(): void
    {
        DB::rollBack();
        try {
            $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::VIEW_BRANDS));

            $brand = Brand::factory()->create();

            $response = $this->post(
                route('admin.brands.db.import', ['brand' => $brand]),
                [
                    'modules' => array_values(['Department' => 'Department',
                        'Desk' => 'Desk',
                        'Geo' => 'Geo',
                        'Language' => 'Language',
                        'Role' => 'Role',
                        'Token' => 'Token','User' => 'User']),
                ],
                [
                    'Tenant' => $brand->slug,
                ]
            );
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
        dd($response->json());
    }
}
