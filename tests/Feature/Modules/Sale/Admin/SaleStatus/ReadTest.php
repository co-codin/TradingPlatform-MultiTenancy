<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Sale\Admin\SaleStatus;

use Modules\Sale\Enums\SaleStatusPermission;
use Modules\Sale\Models\SaleStatus;
use Tests\BrandTestCaseV2;
use Tests\Traits\HasAuth;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;

class ReadTest extends BrandTestCaseV2
{
    use TenantAware, HasAuth;

    /**
     * Test authorized user can get salestatus list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_salestatus_list(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::VIEW_SALE_STATUSES));

        $this->brand->makeCurrent();

        $saleStatus = SaleStatus::factory()->create();

        $response = $this->getJson(route('admin.sale-statuses.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [$saleStatus->toArray()],
        ]);
    }
    /**
     * Test unauthorized user cant get salestatus list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_get_salestatus_list(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        SaleStatus::factory()->create();

        $response = $this->getJson(route('admin.sale-statuses.index'));

        $response->assertForbidden();
    }
    /**
     * Test unauthorized user get salestatus list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_get_salestatus_list(): void
    {
        $this->brand->makeCurrent();

        SaleStatus::factory()->create();

        $response = $this->getJson(route('admin.sale-statuses.index'));

        $response->assertUnauthorized();
    }



    /**
     * Test authorized user can get salestatus by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_salestatus(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::VIEW_SALE_STATUSES));

        $this->brand->makeCurrent();

        $saleStatus = SaleStatus::factory()->create();

        $response = $this->getJson(route('admin.sale-statuses.show', ['sale_status' => $saleStatus->id]));

        $response->assertOk();

        $response->assertJson(['data' => $saleStatus->toArray()]);
    }

    /**
     * Test authorized user can get salestatus by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_get_salestatus(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $saleStatus = SaleStatus::factory()->create();

        $response = $this->getJson(route('admin.sale-statuses.show', ['sale_status' => $saleStatus->id]));

        $response->assertForbidden();
    }
    /**
     * Test authorized user can get not found salestatus by ID.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_not_found_salestatus(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::VIEW_SALE_STATUSES));

        $this->brand->makeCurrent();

        $saleStatusId = SaleStatus::orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->getJson(route('admin.sale-statuses.show', ['sale_status' => $saleStatusId]));

        $response->assertNotFound();
    }
    /**
     * Test unauthorized user can get salestatus by ID.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_can_get_salestatus(): void
    {
        $this->brand->makeCurrent();

        $saleStatus = SaleStatus::factory()->create();

        $response = $this->getJson(route('admin.sale-statuses.show', ['sale_status' => $saleStatus->id]));

        $response->assertUnauthorized();
    }
}
