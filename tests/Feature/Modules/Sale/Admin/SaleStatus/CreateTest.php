<?php

namespace Tests\Feature\Modules\Sale\Admin\SaleStatus;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Sale\Enums\SaleStatusPermission;
use Modules\Sale\Models\SaleStatus;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test authorized user can create salestatus.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_salestatus(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALESTATUS));

        $data = SaleStatus::factory()->make()->toArray();

        $response = $this->postJson(route('admin.salestatus.store'), $data);

        $response->assertCreated();

        $response->assertJson([
            'data' => [
                'name' => $data['name'],
                'title' => $data['title'],
                'color' => $data['color'],
            ],
        ]);
    }

    /**
     * Test unauthorized user can`t create salestatus.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_create_salestatus(): void
    {
        $data = SaleStatus::factory()->make()->toArray();

        $response = $this->postJson(route('admin.salestatus.store'), $data);

        $response->assertUnauthorized();
    }

    /**
     * Test salestatus name exist.
     *
     * @return void
     *
     * @test
     */
    public function salestatus_name_exist(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALESTATUS));

        $data = SaleStatus::factory()->make()->toArray();
        unset($data['name']);

        $response = $this->postJson(route('admin.salestatus.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test salestatus title exist.
     *
     * @return void
     *
     * @test
     */
    public function salestatus_title_exist(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALESTATUS));

        $data = SaleStatus::factory()->make()->toArray();
        unset($data['title']);

        $response = $this->postJson(route('admin.salestatus.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test salestatus color exist.
     *
     * @return void
     *
     * @test
     */
    public function salestatus_color_exist(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALESTATUS));

        $data = SaleStatus::factory()->make()->toArray();
        unset($data['color']);

        $response = $this->postJson(route('admin.salestatus.store'), $data);

        $response->assertUnprocessable();
    }
}
