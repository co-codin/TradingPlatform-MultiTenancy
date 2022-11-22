<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Queue;
use Modules\Brand\Models\Brand;
use Tests\Traits\HasAuth;

abstract class BrandTestCase extends BaseTestCase
{
    use CreatesApplication, HasAuth;

    public Brand $brand;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        $this->brand = Brand::factory()->create();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        dd($this->brand->delete());
        parent::tearDown();

    }
}
