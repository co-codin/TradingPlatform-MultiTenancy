<?php

namespace Tests\Feature\Modules\Customer\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Tests\TestCase;

class ReadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can get customer list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_customer_list(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::VIEW_CUSTOMERS));

        $customers = Customer::factory()->create();

        $response = $this->getJson(route('admin.customers.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [$customers->toArray()],
        ]);
    }
}
