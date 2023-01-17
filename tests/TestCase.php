<?php

declare(strict_types=1);

namespace Tests;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\HasAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
//    use DatabaseMigrations;
    use HasAuth;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
