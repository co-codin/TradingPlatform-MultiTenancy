<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\HasAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions, HasAuth;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
    }
}
