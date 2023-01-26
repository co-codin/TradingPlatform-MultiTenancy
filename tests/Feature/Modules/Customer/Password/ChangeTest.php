<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer\Password;

use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ChangeTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function accepted(): void
    {
        $this->authenticateWithPermission(
            CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS)
        );

        $this->brand->makeCurrent();

        $customer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $customer->save();

        $response = $this->post(route('admin.customers.password.change', ['customer' => $customer]), [
            'password' => 'Passw0rd%',
        ]);

        $response->assertOk();
        $response->assertJsonStructure(['data' => ['password']]);
    }

    /**
     * @test
     */
    public function notFound(): void
    {
        $this->authenticateWithPermission(
            CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS)
        );

        $this->brand->makeCurrent();

        $customerId = Customer::orderByDesc('id')->first()?->id ?? 1;

        $response = $this->post(route('admin.customers.password.change', ['customer' => $customerId]), [
            'password' => 'Passw0rd%',
        ]);

        $response->assertNotFound();
    }
}
