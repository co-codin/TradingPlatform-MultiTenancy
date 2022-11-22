<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
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

        $this->brand = Brand::factory()->create();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        $this->brand->delete();

        parent::tearDown();

    }
}
