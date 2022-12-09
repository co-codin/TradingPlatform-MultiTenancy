<?php

declare(strict_types=1);

namespace Tests;

use DB;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Modules\Brand\Models\Brand;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Spatie\Multitenancy\Models\Tenant;

abstract class BrandTestCase extends BaseTestCase
{
    use CreatesApplication;
    use UsesMultitenancyConfig;

    public Brand $brand;
    protected static $setUpRun = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$setUpRun) {
            Artisan::call('migrate:fresh --seed');
            static::$setUpRun = true;
        }

        $this->brand = Brand::first();
    }


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
}
