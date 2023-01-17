<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Currency\Admin;

use Modules\Currency\Enums\CurrencyPermission;
use Modules\Currency\Models\Currency;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(
            CurrencyPermission::fromValue(CurrencyPermission::EDIT_CURRENCIES)
        );

        Currency::truncate();
        $currency = Currency::factory()->create();
        $currencyData = Currency::factory()->make()->toArray();

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

        Currency::truncate();
        $currency = Currency::factory()->create();
        $data = Currency::factory()->make();

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

        Currency::truncate();
        $currencyId = Currency::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = Currency::factory()->make();

        $response = $this->patch(route('admin.currencies.update', ['currency' => $currencyId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        Currency::truncate();
        $currency = Currency::factory()->create();

        $response = $this->patch(route('admin.currencies.update', ['currency' => $currency]));

        $response->assertUnauthorized();
    }
}
