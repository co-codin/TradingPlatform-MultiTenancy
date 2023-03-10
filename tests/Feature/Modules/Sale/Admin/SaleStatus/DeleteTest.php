<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Sale\Admin\SaleStatus;

use Modules\Sale\Enums\SaleStatusPermission;
use Modules\Sale\Models\SaleStatus;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;

class DeleteTest extends BrandTestCase
{
    use TenantAware, HasAuth;

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

        $this->brand->makeCurrent();

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

        $this->brand->makeCurrent();

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

        $this->brand->makeCurrent();

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
        $this->brand->makeCurrent();

        $saleStatus = SaleStatus::factory()->create();

        $response = $this->patchJson(route('admin.sale-statuses.destroy', ['sale_status' => $saleStatus->id]));

        $response->assertUnauthorized();
    }
}
