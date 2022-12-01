<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Sale\Admin\SaleStatus;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Sale\Enums\SaleStatusPermission;
use Modules\Sale\Models\SaleStatus;
use Tests\TestCase;

class ReadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can get salestatus list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_salestatus_list(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::VIEW_SALESTATUS));

        $salestatus = SaleStatus::factory()->create();

        $response = $this->getJson(route('admin.salestatus.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                $salestatus->toArray(),
            ],
        ]);
    }

    /**
     * Test unauthorized user cant get salestatus list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_get_salestatus_list(): void
    {
        SaleStatus::factory()->create();

        $response = $this->getJson(route('admin.salestatus.index'));

        // dd($response);
        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get salestatus list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_salestatus(): void
    {
        $this->authenticateWithPermission(SaleStatusPermission::fromValue(SaleStatusPermission::VIEW_SALESTATUS));

        $salestatus = SaleStatus::factory()->create();

        $response = $this->getJson(route('admin.salestatus.show', ['salestatus' => $salestatus->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $salestatus->id,
                'name' => $salestatus->name,
                'title' => $salestatus->title,
                'color' => $salestatus->color,
            ],
        ]);
    }

    /**
     * Test unauthorized user cant get salestatus list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_get_salestatus(): void
    {
        $salestatus = SaleStatus::factory()->create();

        $response = $this->getJson(route('admin.salestatus.show', ['salestatus' => $salestatus->id]));

        $response->assertUnauthorized();
    }
}
