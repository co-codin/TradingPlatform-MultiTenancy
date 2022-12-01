<?php

namespace Tests\Feature\Modules\Sale\Admin\SaleStatus;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Sale\Enums\SaleStatusPermission;
use Modules\Sale\Models\SaleStatus;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can update salestatus.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_salestatus(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::EDIT_SALE_STATUSES));

        $saleStatus = SaleStatus::factory()->create();
        $data = SaleStatus::factory()->make();

        $response = $this->patchJson(route('admin.sale-statuses.update', ['sale_status' => $saleStatus->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => $data->toArray(),
        ]);
    }
    /**
     * Test authorized user can`t update salestatus.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_cant_update_salestatus(): void
    {
        $this->authenticateUser();

        $saleStatus = SaleStatus::factory()->create();
        $data = SaleStatus::factory()->make();

        $response = $this->patchJson(route('admin.sale-statuses.update', ['sale_status' => $saleStatus->id]), $data->toArray());

        $response->assertForbidden();
    }
    /**
     * Test authorized user can update not found salestatus.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_not_found_salestatus(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::EDIT_SALE_STATUSES));

        $saleStatusId = SaleStatus::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = SaleStatus::factory()->make();

        $response = $this->patchJson(route('admin.sale-statuses.update', ['sale_status' => $saleStatusId]), $data->toArray());

        $response->assertNotFound();
    }
    /**
     * Test unauthorized user can update salestatus.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized(): void
    {
        $saleStatus = SaleStatus::factory()->create();
        $data = SaleStatus::factory()->make();

        $response = $this->patchJson(route('admin.sale-statuses.update', ['sale_status' => $saleStatus->id]), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test salestatus name exist.
     *
     * @return void
     *
     * @test
     */
    final public function salestatus_name_exist(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::EDIT_SALE_STATUSES));

        $saleStatus = SaleStatus::factory()->create();
        $data = SaleStatus::factory()->make(['name' => null])->toArray();

        $response = $this->patchJson(route('admin.sale-statuses.update', ['sale_status' => $saleStatus->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test salestatus title exist.
     *
     * @return void
     *
     * @test
     */
    final public function salestatus_title_exist(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::EDIT_SALE_STATUSES));

        $saleStatus = SaleStatus::factory()->create();
        $data = SaleStatus::factory()->make(['title' => null])->toArray();

        $response = $this->patchJson(route('admin.sale-statuses.update', ['sale_status' => $saleStatus->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test salestatus color exist.
     *
     * @return void
     *
     * @test
     */
    final public function salestatus_color_exist(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::EDIT_SALE_STATUSES));

        $saleStatus = SaleStatus::factory()->create();
        $data = SaleStatus::factory()->make(['color' => null])->toArray();

        $response = $this->patchJson(route('admin.sale-statuses.update', ['sale_status' => $saleStatus->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test salestatus incorrect color format.
     *
     * @return void
     *
     * @test
     */
    final public function salestatus_incorrect_color_format(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::EDIT_SALE_STATUSES));

        $saleStatus = SaleStatus::factory()->create();
        $data = SaleStatus::factory()->make(['color' => '#e1e1'])->toArray();

        $response = $this->patchJson(route('admin.sale-statuses.update', ['sale_status' => $saleStatus->id]), $data);

        $response->assertUnprocessable();
    }
}
