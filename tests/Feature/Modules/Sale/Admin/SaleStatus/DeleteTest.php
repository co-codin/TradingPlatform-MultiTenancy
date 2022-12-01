<?php

namespace Tests\Feature\Modules\Sale\Admin\SaleStatus;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Sale\Enums\SaleStatusPermission;
use Modules\Sale\Models\SaleStatus;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can delete salestatus.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_delete_salestatus(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::DELETE_SALE_STATUSES));

        $saleStatus = SaleStatus::factory()->create();

        $response = $this->deleteJson(route('admin.sale-statuses.destroy', ['sale_status' => $saleStatus->id]));

        $response->assertNoContent();
    }

    /**
     * Test authorized user can`t delete salestatus.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_cant_delete_salestatus(): void
    {
        $this->authenticateUser();

        $saleStatus = SaleStatus::factory()->create();

        $response = $this->deleteJson(route('admin.sale-statuses.destroy', ['sale_status' => $saleStatus->id]));

        $response->assertForbidden();
    }

    /**
     * Test authorized user can delete not found salestatus.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_delete_not_found_salestatus(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::DELETE_SALE_STATUSES));

        $saleStatusId = SaleStatus::orderByDesc('id')->first()?->id + 1 ?? 1;
        $response = $this->delete(route('admin.sale-statuses.destroy', ['sale_status' => $saleStatusId]));

        $response->assertNotFound();
    }

    /**
     * Test unauthorized user can`t delete salestatus.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized(): void
    {
        $saleStatus = SaleStatus::factory()->create();

        $response = $this->patchJson(route('admin.sale-statuses.destroy', ['sale_status' => $saleStatus->id]));

        $response->assertUnauthorized();
    }
}
