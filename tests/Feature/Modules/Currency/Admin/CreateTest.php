<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Currency\Admin;

use Modules\Currency\Enums\CurrencyPermission;
use Modules\Currency\Models\Currency;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_create(): void
    {
         $this->authenticateWithPermission(
             CurrencyPermission::fromValue(CurrencyPermission::CREATE_CURRENCIES)
         );

         $this->brand->makeCurrent();

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

        $this->brand->makeCurrent();

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
