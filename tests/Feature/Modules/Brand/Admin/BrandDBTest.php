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
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class BrandDBTest extends BrandTestCase
{
//    use DatabaseTransactions;
//
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
        try {

            $user = User::whereEmail('test@service.com')->first() ??
                User::factory()->withParent()->create([
                    'email' => 'test@service.com',
                ]);

            $permission = Permission::where(
                    'name',
                    BrandPermission::fromValue(
                        BrandPermission::VIEW_BRANDS
                    )->value
                )->first() ??
                Permission::factory()->create([
                    'name' => BrandPermission::fromValue(BrandPermission::VIEW_BRANDS)->value,
                ]);

            $user->givePermissionTo($permission);

            $this->brand->users()->attach($user);

            $response = $this->actingAs($user, User::DEFAULT_AUTH_GUARD)->post(
                route('admin.brands.db.import', ['brand' => $this->brand]),
                [
                    'modules' => array_values(['Department' => 'Department',
                        'Desk' => 'Desk',
                        'Geo' => 'Geo',
                        'Language' => 'Language',
                        'Role' => 'Role',
                        'Token' => 'Token', 'User']),
                ],
                [
                    'Tenant' => $this->brand->slug,
                ]
            );
        } catch (\Throwable $e) {
            dd($e->getTrace());
        }
        dd($response->json());
    }
}
