<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer\Admin;

use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Modules\Role\Enums\ModelHasPermissionStatus;
use Modules\Role\Models\Permission;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    /**
     * Test authorized user can update customer.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_update_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customer->save();

        $data = Customer::factory()->make();
        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data->toArray());
        $response->assertOk();

        $response->assertJsonStructure([
            'data' => array_keys($data->toArray()),
        ]);
    }

    /**
     * Test authorized user can update customer.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_update_customer_permissions_with_pivot(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customer->save();

        $data['permissions'] = [
            [
                'id' => Permission::factory()->create()->id,
                'status' => ModelHasPermissionStatus::ACTIVE,
            ],
            [
                'id' => Permission::factory()->create()->id,
                'status' => ModelHasPermissionStatus::ACTIVE,
                'data' => [
                    'reason' => 'reason',
                ],
            ],
            [
                'id' => Permission::factory()->create()->id,
                'status' => ModelHasPermissionStatus::SUSPENDED,
                'data' => [
                    'reason' => 'reason',
                ],
            ],
        ];

        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data);

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => array_keys($data),
        ]);
    }

    /**
     * Test unauthorized user can`t update customer.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_update_customer(): void
    {
        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customer->save();

        $data = Customer::factory()->make();
        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user cant update not existed customer.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_update_not_existed_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $this->brand->makeCurrent();

        $customerId = Customer::orderByDesc('id')->first()?->id ?? 1;
        $data = Customer::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customerId]), $data->toArray());

        $response->assertNotFound();
    }

    /**
     * Test cant update existed first conversion user id.
     *
     * @return void
     *
     * @test
     */
    public function cant_update_existed_first_conversion_user_id(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customer->save();

        $data = Customer::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'conversion_user_id' => $data->conversion_user_id,
                'first_conversion_user_id' => $customer->first_conversion_user_id,
            ],
        ]);
    }

    /**
     * Test cant update existed first retention user id.
     *
     * @return void
     *
     * @test
     */
    public function cant_update_existed_first_retention_user_id(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS));

        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customer->save();

        $data = Customer::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.customers.update', ['customer' => $customer->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'retention_user_id' => $data->retention_user_id,
                'first_retention_user_id' => $customer->first_retention_user_id,
            ],
        ]);
    }
}
