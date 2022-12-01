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
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::DELETE_SALESTATUS));

        $salestatus = SaleStatus::factory()->create();

        $response = $this->deleteJson(route('admin.salestatus.destroy', ['salestatus' => $salestatus->id]));

        $response->assertNoContent();
    }

    /**
     * Test unauthorized user can`t delete salestatus.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized_user_cant_delete_salestatus(): void
    {
        $salestatus = SaleStatus::factory()->create();

        $response = $this->patchJson(route('admin.salestatus.destroy', ['salestatus' => $salestatus->id]));

        $response->assertUnauthorized();
    }
}
