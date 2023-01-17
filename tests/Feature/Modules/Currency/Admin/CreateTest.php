<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Currency\Admin;

use Modules\Currency\Enums\CurrencyPermission;
use Modules\Currency\Models\Currency;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(
            CurrencyPermission::fromValue(CurrencyPermission::CREATE_CURRENCIES)
        );

        Currency::truncate();
        $data = Currency::factory()->make()->toArray();

        $response = $this->post(route('admin.currencies.store'), $data);

        $response->assertCreated();
        $response->assertJson(['data' => $data]);
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        Currency::truncate();
        $data = Currency::factory()->make()->toArray();

        $response = $this->post(route('admin.currencies.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.currencies.store'));

        $response->assertUnauthorized();
    }
}
