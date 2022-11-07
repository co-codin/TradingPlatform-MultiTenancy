<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Traits\RefreshesPermissionCache;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, RefreshesPermissionCache;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('optimize');
    }
}

