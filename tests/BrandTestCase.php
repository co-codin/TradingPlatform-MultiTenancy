<?php

declare(strict_types=1);

namespace Tests;

use App\Tenant\TestingTenantManager;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Modules\Brand\Models\Brand;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;

abstract class BrandTestCase extends BaseTestCase
{
    use CreatesApplication;
    use UsesMultitenancyConfig;
    protected static $setUpRun = false;

    public Brand $brand;

    protected TestingTenantManager $testingTenantManager;

    public static function tearDownAfterClass(): void
    {
        $instance = new static();

        $instance->refreshApplication();

        $instance->testingTenantManager->forget();

        $instance->tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $this->brand = $this->testingTenantManager->getBrand();

        Artisan::call('migrate:refresh --seed');
//        if (! static::$setUpRun) {
//            Artisan::call('migrate:fresh --seed');
//            static::$setUpRun = true;
//        }
//
//        $this->brand = Brand::first();
    }

    /**
     * Make current tenant and set header.
     *
     * @return void
     */
    protected function makeCurrentTenantAndSetHeader(): void
    {
        $this->brand->makeCurrent();

        $this->withHeader('tenant', $this->brand->database);
    }
}
