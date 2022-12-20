<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer\Impersonate;

use Illuminate\Support\Facades\Auth;
use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Modules\User\Http\Controllers\Admin\AuthController;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class SessionTest extends BrandTestCase
{
    use HasAuth;
    use TenantAware;

    private Customer $targetCustomer;

    /**
     * @test
     */
    public function can_impersonate(): void
    {
        $this->authenticateWithPermission(
            CustomerPermission::fromValue(CustomerPermission::IMPERSONATE_CUSTOMERS),
            AuthController::GUARD
        );

        $this->brand->makeCurrent();
        $customer = $this->targetCustomer;
        $response = $this->post(route('admin.customers.impersonate.session', ['id' => $customer->id]));

        $response->assertOk();
        $response->assertSessionHasAll([
            'impersonator_id' => $this->getUser()->id, 'impersonator_remember_me' => false,
        ]);
        $response->assertJsonStructure([
            'impersonator',
            'target_customer',
        ]);
        $this->assertAuthenticatedAs($customer, Customer::DEFAULT_AUTH_GUARD);
    }

    /**
     * @test
     */
    public function can_impersonate_with_remember(): void
    {
        $this->authenticateWithPermission(
            CustomerPermission::fromValue(CustomerPermission::IMPERSONATE_CUSTOMERS),
            AuthController::GUARD
        );

        $this->brand->makeCurrent();
        $customer = $this->targetCustomer;
        $impersonator = $this->getUser();
        $rememberToken = vsprintf('%s|%s|%s', [
            $impersonator->id,
            $impersonator->getRememberToken(),
            $impersonator->password,
        ]);
        $response = $this->withCookie(Auth::guard(AuthController::GUARD)->getRecallerName(), $rememberToken)
            ->post(route('admin.customers.impersonate.session', ['id' => $customer->id]));

        $response->assertOk();
        $response->assertSessionHasAll(['impersonator_id' => $impersonator->id, 'impersonator_remember_me' => true]);
        $response->assertJsonStructure([
            'impersonator',
            'target_customer',
        ]);
        $this->assertAuthenticatedAs($customer, Customer::DEFAULT_AUTH_GUARD);
    }

    /**
     * @test
     */
    public function cant_impersonate(): void
    {
        $this->authenticateUser(AuthController::GUARD);

        $this->brand->makeCurrent();
        $response = $this->post(route('admin.customers.impersonate.session', ['id' => $this->targetCustomer->id]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();
        $response = $this->post(route('admin.customers.impersonate.session', ['id' => $this->targetCustomer->id]));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->brand->makeCurrent();
        $this->targetCustomer = $this->brand->execute(function () {
            return Customer::factory()->make();
        });

        $this->targetCustomer->save();
    }
}
