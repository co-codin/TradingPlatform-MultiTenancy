<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Currency\Admin;

use Modules\Currency\Enums\CurrencyPermission;
use Modules\Currency\Models\Currency;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(
            CurrencyPermission::fromValue(CurrencyPermission::EDIT_CURRENCIES)
        );

        $this->brand->makeCurrent();

        Currency::truncate();
        $currency = Currency::factory()->create();
        $currencyData = Currency::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.currencies.update', ['currency' => $currency]), $currencyData);

        $response->assertOk();
        $response->assertJson(['data' => $currencyData]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        Currency::truncate();
        $currency = Currency::factory()->create();
        $data = Currency::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(
            route('admin.currencies.update', ['currency' => $currency]), $data->toArray()
        );

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $currencyId = Currency::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = Currency::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.currencies.update', ['currency' => $currencyId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();
        $currency = Currency::factory()->create();

        $response = $this->patch(route('admin.currencies.update', ['currency' => $currency]));

        $response->assertUnauthorized();
    }
}
