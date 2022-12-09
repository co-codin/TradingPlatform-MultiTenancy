<?php

declare(strict_types=1);

namespace Tests;

use DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Modules\Brand\Models\Brand;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;

abstract class BrandTestCaseV2 extends BaseTestCase
{
    use CreatesApplication;
    use UsesMultitenancyConfig;

    protected static $setUpRun = false;

    protected function setUp(): void
    {
        parent::setUp();
        if (!static::$setUpRun) {
            // $schemas = Brand::get();
            // foreach ($schemas as $schema) {
            //     DB::unprepared("DROP SCHEMA IF EXISTS {$schema->database} CASCADE;");
            // }
            // Artisan::call('migrate:fresh --seed');
            static::$setUpRun = true;
        }

        Brand::first()->makeCurrent();
    }
}
