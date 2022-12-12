<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Brand\Models\Brand;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;

abstract class BrandTestCase extends BaseTestCase
{
    use CreatesApplication;
    use UsesMultitenancyConfig;
    protected static $setUpRun = false;

    public Brand $brand;

    public static function tearDownAfterClass(): void
    {
        $instance = new static();
        $instance->refreshApplication();

        $schemas = Brand::get();
        foreach ($schemas as $schema) {
            DB::unprepared("DROP SCHEMA IF EXISTS {$schema->database} CASCADE;");
        }

        Artisan::call('migrate:reset');

        $instance->tearDown();
    }

    protected function setUp(): void
    {
        parent::setUp();

        if (! static::$setUpRun) {
            Artisan::call('migrate:fresh --seed');
            static::$setUpRun = true;
        }

        $this->brand = Brand::first();
    }
}
