<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Geo\Country\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can create country.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_country(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $data = Country::factory()->make(static::demoData())->toArray();

        $response = $this->postJson(route('admin.countries.store'), $data);

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }

    /**
     * Test unauthorized user can`t create country.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_create_country(): void
    {
        $data = Country::factory()->make();

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

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
