<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Geo\Country\Admin;

use Illuminate\Support\Str;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can delete country.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_delete_country(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::DELETE_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());

        $response = $this->deleteJson(route('admin.countries.destroy', ['country' => $country->id]));

        $response->assertNoContent();
    }

    /**
     * Test unauthorized user can`t delete country.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_delete_country(): void
    {
        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());

        $response = $this->patchJson(route('admin.countries.destroy', ['country' => $country->id]));

        $response->assertUnauthorized();
    }

    /**
     * Demo Data
     *
     * @return array
     */
    private function demoData(): array
    {
        return [
            'name' => Str::random(5),
            'iso2' => Str::random(5),
            'iso3' => Str::random(5),
            'currency' => 'CUR',
        ];
    }
}
