<?php

namespace Tests\Feature\Modules\Sale\Admin\SaleStatus;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Sale\Enums\SaleStatusPermission;
use Modules\Sale\Models\SaleStatus;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions, SaleStatusAdminTrait;
    /**
     * Test authorized user can create salestatus.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_salestatus(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALE_STATUSES));

        $data = SaleStatus::factory()->make()->toArray();

        $response = $this->postJson(route('admin.sale-statuses.store'), $data);

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }

    /**
     * Test authorized user can`t create salestatus.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_create_salestatus(): void
    {
        $this->authenticateUser();

        $data = SaleStatus::factory()->make()->toArray();

        $response = $this->postJson(route('admin.sale-statuses.store'), $data);

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user can`t create salestatus.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized(): void
    {
        $data = SaleStatus::factory()->make()->toArray();

        $response = $this->postJson(route('admin.sale-statuses.store'), $data);

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
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALE_STATUSES));

        $data = SaleStatus::factory()->make()->toArray();
        unset($data['name']);

        $response = $this->postJson(route('admin.sale-statuses.store'), $data);

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
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALE_STATUSES));

        $data = SaleStatus::factory()->make()->toArray();
        unset($data['title']);

        $response = $this->postJson(route('admin.sale-statuses.store'), $data);

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
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALE_STATUSES));

        $data = SaleStatus::factory()->make()->toArray();
        unset($data['color']);

        $response = $this->postJson(route('admin.sale-statuses.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test salestatus incorrect color format.
     *
     * @return void
     *
     * @test
     */
    public function salestatus_incorrect_color_format(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALE_STATUSES));

        $data = SaleStatus::factory()->make()->toArray();
        $data['color'] = "#e1e1";

        $response = $this->postJson(route('admin.sale-statuses.store'), $data);

        $response->assertUnprocessable();
    }
}
