<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Geo\Country\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Tests\TestCase;

class ReadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can get countries list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_countries_list(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::VIEW_COUNTRIES));

        $country = Country::factory()->create(static::demoData())->toArray();

        $response = $this->getJson(route('admin.countries.index'));

        $response->assertOk();

        $response->assertJson(['data' => [$country]]);
    }

    /**
     * Test unauthorized user cant get countries list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_get_countries_list(): void
    {
        Country::factory()->create(static::demoData());

        $response = $this->getJson(route('admin.countries.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get countries list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_country(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::VIEW_COUNTRIES));

        $country = Country::factory()->create(static::demoData());

        $response = $this->getJson(route('admin.countries.show', ['country' => $country->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => $country->toArray(),
        ]);
    }

    /**
     * Test unauthorized user cant get countries list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_get_country(): void
    {
        $country = Country::factory()->create(static::demoData());

        $response = $this->getJson(route('admin.countries.show', ['country' => $country->id]));

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
