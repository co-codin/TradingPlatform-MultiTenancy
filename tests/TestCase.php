<?php

declare(strict_types=1);

namespace Tests;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Tests\Traits\HasAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    use HasAuth;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        if ($token = \Illuminate\Support\Facades\ParallelTesting::token()) {

            $dbname = 'testing_test_' . $token;

            config([
                "database.connections.pgsql.database" => $dbname,
                "database.connections.tenant.database" => $dbname,
                "database.connections.landlord.database" => $dbname,
            ]);
        }

        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
