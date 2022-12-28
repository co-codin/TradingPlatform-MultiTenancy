<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Currency\Admin;

use Modules\Currency\Enums\CurrencyPermission;
use Modules\Currency\Models\Currency;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_delete(): void
    {
        $this->authenticateWithPermission(
            CurrencyPermission::fromValue(CurrencyPermission::DELETE_CURRENCIES)
        );

        $this->brand->makeCurrent();

        Currency::truncate();
        $currency = Currency::factory()->create();

        $response = $this->delete(route('admin.currencies.destroy', ['currency' => $currency]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function cant_delete(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        Currency::truncate();
        $currency = Currency::factory()->create();

        $response = $this->delete(route('admin.currencies.destroy', ['currency' => $currency]));

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

        $response = $this->delete(route('admin.currencies.destroy', ['currency' => $currencyId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        Currency::truncate();
        $currency = Currency::factory()->create();

        $response = $this->delete(route('admin.currencies.destroy', ['currency' => $currency]));

        $response->assertUnauthorized();
    }
}
