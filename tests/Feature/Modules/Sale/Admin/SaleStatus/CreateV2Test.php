<?php

namespace Tests\Feature\Modules\Sale\Admin\SaleStatus;

use Modules\Brand\Models\Brand;
use Modules\Sale\Enums\SaleStatusPermission;
use Modules\Sale\Models\SaleStatus;
use Tests\BrandTestCaseV2;
use Tests\Traits\HasAuth;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;

class CreateV2Test extends BrandTestCaseV2
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can create salestatus.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_salestatus_v2(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALE_STATUSES));

        $this->brand->makeCurrent();

        $data = SaleStatus::factory()->make()->toArray();

        $response = $this->postJson(route('admin.sale-statuses.store'), $data);

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }
    /**
     * Test authorized user can create salestatus.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_salestatus_v3(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALE_STATUSES));

        $this->brand->makeCurrent();

        $data = SaleStatus::factory()->make()->toArray();

        $response = $this->postJson(route('admin.sale-statuses.store'), $data);

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }
    /**
     * Test authorized user can create salestatus.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_salestatus_v4(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::CREATE_SALE_STATUSES));

        $this->brand->makeCurrent();

        $data = SaleStatus::factory()->make()->toArray();

        $response = $this->postJson(route('admin.sale-statuses.store'), $data);

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }
}
