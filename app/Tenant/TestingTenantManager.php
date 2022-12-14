<?php

declare(strict_types=1);

namespace App\Tenant;

use Illuminate\Support\Facades\Artisan;
use Modules\Brand\Models\Brand;

final class TestingTenantManager
{
    /**
     * @var Brand
     */
    private Brand $brand;

    public function __construct(?Brand $brand = null)
    {
        $this->brand ??= $brand ?? Brand::first() ?? Brand::factory()->create();
    }

    public function makeCurrent(): self
    {
        $this->brand->makeCurrent();

        Artisan::call('migrate:refresh --seed');

        return $this;
    }

    public function forget(): self
    {
        $this->brand->forget();

        Artisan::call('migrate:reset');

        return $this;
    }

    public function getBrand(): Brand
    {
        return $this->brand;
    }
}
