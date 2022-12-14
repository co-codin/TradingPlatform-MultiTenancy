<?php

declare(strict_types=1);

namespace Tests;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Brand\Models\Brand;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;

abstract class BrandTestCase extends BaseTestCase
{
    use CreatesApplication;
    use UsesMultitenancyConfig;

    /**
     * @var bool
     */
    protected static bool $setUpRun = false;

    /**
     * @var Brand
     */
    public Brand $brand;

//    public static function tearDownAfterClass(): void
//    {
//        $instance = new static();
//        $instance->refreshApplication();
//
//        $schemas = DB::select("SELECT schema_name FROM information_schema.schemata WHERE schema_name NOT IN ('pg_toast', 'pg_catalog', 'public', 'information_schema', {$instance->brand->database})");
//        foreach ($schemas as $schema) {
//            DB::unprepared("DROP SCHEMA IF EXISTS {$schema->schema_name} CASCADE;");
//        }
//
//        Artisan::call('migrate:reset');
//
//        $instance->tearDown();
//    }

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        try {
            $this->withoutMiddleware(VerifyCsrfToken::class);

            if (! static::$setUpRun) {
                Artisan::call('migrate:fresh --seed');
                static::$setUpRun = true;
            }

            $this->brand = Brand::factory()->create();
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $schemas = DB::select("
                SELECT schema_name
                FROM information_schema.schemata
                WHERE schema_name NOT IN ('pg_toast', 'pg_catalog', 'public', 'information_schema', {$this->brand->database})
            ");

        foreach ($schemas as $schema) {
            DB::unprepared("DROP SCHEMA IF EXISTS {$schema->schema_name} CASCADE;");
        }

        Artisan::call('migrate:reset');

        parent::tearDown();
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
