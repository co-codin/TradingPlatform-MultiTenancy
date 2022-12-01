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
     * test_authorized_user_can_get_salestatus_list
     *
     * @return void
     */
    public function test_authorized_user_can_get_salestatus_list(): void
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
}
