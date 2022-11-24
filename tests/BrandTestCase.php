<?php

declare(strict_types=1);

namespace Tests;

use App\Services\Tenant\Manager;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;
use Modules\Brand\Events\Tenant\BrandTenantIdentified;
use Modules\Brand\Jobs\MigrateStructureJob;
use Modules\Brand\Models\Brand;
use Nwidart\Modules\Facades\Module;
use Tests\Traits\HasAuth;

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

        $this->brand = Brand::factory()->create();

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
