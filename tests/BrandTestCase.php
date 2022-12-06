<?php

declare(strict_types=1);

namespace Tests;

use App\Services\Tenant\DatabaseManager;
use App\Services\Tenant\DatabaseManipulator;
use App\Services\Tenant\Manager;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;
use Modules\Brand\Models\Brand;
use Modules\Brand\Services\BrandDBService;

abstract class BrandTestCase extends BaseTestCase
{
    use CreatesApplication;

    public Brand $brand;

    public $db;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->brand = Brand::factory()->create();

        (new DatabaseManipulator)->createSchema($this->brand->slug);

        app(Manager::class)->setTenant($this->brand);

        $this->db = $this->app->make(DatabaseManager::class);

        $this->db->createConnection($this->brand)->connectToTenant();

//        $this->migrateModules();

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

    public function migrateModules()
    {
        (new BrandDBService($this->brand))
            >setBrand($this->brand)
            ->setModules(BrandDBService::REQUIRED_MODULES)
            ->migrateDB()
            ->seedData();
//        MigrateSchemaJob::dispatchSync($this->brand, $modules, $availableModules ?: array_keys(Module::all()));
    }
}
