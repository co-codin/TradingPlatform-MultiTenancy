<?php

declare(strict_types=1);

namespace Tests;

use DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Event;
use Modules\Brand\Models\Brand;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Spatie\Multitenancy\Events\MadeTenantCurrentEvent;

abstract class BrandTestCaseV2 extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions, UsesMultitenancyConfig;

    protected function connectionsToTransact()
    {
        return [
            $this->landlordDatabaseConnectionName(),
            $this->tenantDatabaseConnectionName(),
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        Event::listen(MadeTenantCurrentEvent::class, function () {
            $this->beginDatabaseTransaction();
        });

        Brand::first()->makeCurrent();
    }
}
