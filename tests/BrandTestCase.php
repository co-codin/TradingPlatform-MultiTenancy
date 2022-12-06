<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Testing\TestResponse;
use Modules\Brand\Jobs\MigrateStructureJob;
use Modules\Brand\Models\Brand;
use Nwidart\Modules\Facades\Module;

abstract class BrandTestCase extends BaseTestCase
{
    use CreatesApplication;

    public Brand $brand;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
try {

    $this->brand = Brand::factory()->create();
    dd('a');
} catch (\Throwable $e) {
    dd($e->getMessage());
}
        $this->withHeader('Tenant', $this->brand->slug);
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

    public function migrateModules(array $modules, array $availableModules = [])
    {
        MigrateStructureJob::dispatchSync($this->brand, $modules, $availableModules ?: array_keys(Module::all()));
    }
}
