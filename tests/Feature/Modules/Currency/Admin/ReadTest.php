<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Currency\Admin;

use Modules\Currency\Enums\CurrencyPermission;
use Modules\Currency\Models\Currency;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_view_any(): void
    {
        $this->authenticateWithPermission(
            CurrencyPermission::fromValue(CurrencyPermission::VIEW_CURRENCIES)
        );

        $this->brand->makeCurrent();

        Currency::truncate();
        $currencies = Currency::factory(10)->create();

        $response = $this->get(route('admin.currencies.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => $currencies->only('id')->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $response = $this->get(route('admin.currencies.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(
            CurrencyPermission::fromValue(CurrencyPermission::VIEW_CURRENCIES)
        );

        $this->brand->makeCurrent();

        Currency::truncate();
        $currency = Currency::factory()->create();

        $response = $this->get(route('admin.currencies.show', ['currency' => $currency]));

        $response->assertOk();
        $response->assertJson(['data' => $currency->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        Currency::truncate();
        $currency = Currency::factory()->create();

        $response = $this->get(route('admin.currencies.show', ['currency' => $currency]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();
        $currencyId = Currency::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.currencies.show', ['currency' => $currencyId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.currencies.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $this->brand->makeCurrent();

        Currency::truncate();
        $currency = Currency::factory()->create();

        $response = $this->get(route('admin.currencies.show', ['currency' => $currency]));

        $response->assertUnauthorized();
    }
}
