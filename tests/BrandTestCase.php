<?php

declare(strict_types=1);

namespace Tests;

use App\Services\Tenant\DatabaseManager;
use App\Services\Tenant\DatabaseManipulator;
use App\Services\Tenant\Manager;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Testing\TestResponse;
use Modules\Brand\Models\Brand;
use Modules\Brand\Services\BrandDBService;
use Tests\Traits\HasAuth;

abstract class BrandTestCase extends BaseTestCase
{
    use CreatesApplication, HasAuth;

    public Brand $brand;

    public $db;

    public array $modules;

    public string $connectName;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->modules = array_values(BrandDBService::ALLOWED_MODULES);

        $this->connectionName = Manager::TENANT_CONNECTION_NAME;

        $this->brand = Brand::factory()->create();

        app(Manager::class)->setTenant($this->brand);

        (new DatabaseManipulator)->createSchema($this->brand->slug);

        $this->db = $this->app->make(DatabaseManager::class);

        $this->db->createConnection($this->brand);

        $this->db->purge();


        if (! Schema::connection($this->connectionName)->hasTable('migrations')) {
            Artisan::call(sprintf(
                'migrate:install --database=%s',
                $this->connectionName
            ));
        }

        $forMigrate = array_values(array_diff($this->modules, $this->brand->tables ?? []));
        $forRollback = array_values(array_diff($this->brand->tables ?? [], $this->modules));

        foreach ($forRollback as $module) {
            Artisan::call(sprintf(
                'brand:migrate-rollback %s --database=%s',
                $this->prepareMigrations($module),
                $this->connectionName
            ));
        }

        foreach ($forMigrate as $module) {
            if (in_array($module, $this->modules)) {
                Artisan::call(sprintf(
                    'brand:migrate %s --database=%s',
                    $this->prepareMigrations($module),
                    $this->connectionName
                ));
            }
        }
//        $this->withHeader('Tenant', $this->brand->slug);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $this->brand->delete();

        parent::tearDown();
    }

    /**
     * Import.
     *
     * @param array $modules
     * @return TestResponse
     */
    protected function import(array $modules): TestResponse
    {
        return $this->post(
            route('admin.brands.db.import', ['brand' => $this->brand]),
            [
                'modules' => $modules,
            ]
        );
    }

    private function prepareMigrations($module): string
    {
        $migrations = array_values(
            array_diff(
                scandir(base_path("Modules/{$module}/Database/Migrations")),
                ['..', '.']
            ),
        );

        return implode(' ', Arr::map($migrations, function ($migration) use ($module) {
            foreach (BrandDBService::ALLOWED_RELATIONS as $relation => $modules) {
                if (
                    stripos($migration, $relation) !== false &&
                    ! in_array($modules, $this->modules)
                ) {
                    if (! in_array($modules, $this->modules)) {
                        return false;
                    }
                }
            }

            foreach (BrandDBService::EXCEPT_MIGRATION_KEY_WORDS as $exceptKeyWord) {
                if ( stripos($migration, $exceptKeyWord) !== false) {
                    return false;
                }
            }

            return '--path='.base_path("Modules/{$module}/Database/Migrations/{$migration}");
        }));
    }
}
