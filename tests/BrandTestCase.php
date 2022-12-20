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
     * @var Brand
     */
    public Brand $brand;

    /**
     * {@inheritDoc}
     */
    public static function tearDownAfterClass(): void
    {
        (new static())->refreshApplication();

        $schemas = DB::select("
                SELECT schema_name
                FROM information_schema.schemata
                WHERE schema_name NOT IN ('pg_toast', 'pg_catalog', 'public', 'information_schema');
            ");

        foreach ($schemas as $schema) {
            DB::unprepared("DROP SCHEMA IF EXISTS {$schema->schema_name} CASCADE;");
        }

        Artisan::call('db:wipe', ['--force' => true]);

        parent::tearDownAfterClass();
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(VerifyCsrfToken::class);

        Artisan::call('migrate:fresh');

        $this->brand ??= Brand::factory()->create();
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
