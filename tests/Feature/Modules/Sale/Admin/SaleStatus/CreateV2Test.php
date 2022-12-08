<?php

namespace Tests\Feature\Modules\Sale\Admin\SaleStatus;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Artisan;
use Modules\Sale\Enums\SaleStatusPermission;
use Modules\Sale\Models\SaleStatus;
use Tests\BrandTestCaseV2;
use Tests\Traits\HasAuth;

class CreateV2Test extends BrandTestCaseV2
{
    use RefreshDatabase;
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

        $data = SaleStatus::factory()->make()->toArray();

        $response = $this->postJson(route('admin.sale-statuses.store'), $data);

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }

}
