<?php

namespace Tests\Feature\Modules\Geo\Country\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can update country.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_update_country(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(static::demoData())->toArray();

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data);

        $response->assertOk();

        $response->assertJson(['data' => $data]);
    }

    /**
     * Test unauthorized user can`t update country.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_update_country(): void
    {
        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(static::demoData());

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data->toArray());

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
