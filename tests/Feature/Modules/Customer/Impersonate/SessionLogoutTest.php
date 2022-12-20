<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer\Impersonate;

use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Controllers\Admin\AuthController;
use Modules\User\Models\User;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasCustomerAuth;

final class SessionLogoutTest extends BrandTestCase
{
    use HasCustomerAuth;
    use TenantAware;

    private readonly User $impersonator;

    /**
     * @test
     */
    public function success(): void
    {
        $this->authenticateCustomer();
        $response = $this->withSession([
            'impersonator_id' => $this->impersonator->id, 'impersonator_remember_me' => false,
        ])
            ->post(route('admin.customers.impersonate.session.logout'));

        $response->assertNoContent();
        $response->assertSessionMissing(['impersonator_id', 'impersonator_remember_me']);
        $this->assertAuthenticatedAs($this->impersonator, AuthController::GUARD);
    }

    /**
     * @test
     */
    public function success_with_remember(): void
    {
        $this->authenticateCustomer();
        $response = $this->withSession([
            'impersonator_id' => $this->impersonator->id, 'impersonator_remember_me' => true,
        ])->post(route('admin.customers.impersonate.session.logout'));

        $response->assertNoContent();
        $response->assertSessionMissing(['impersonator_id', 'impersonator_remember_me']);
        $this->assertAuthenticatedAs($this->impersonator, AuthController::GUARD);
        $response->assertCookie(Auth::guard(AuthController::GUARD)->getRecallerName(), vsprintf('%s|%s|%s', [
            $this->impersonator->id,
            $this->impersonator->getRememberToken(),
            $this->impersonator->password,
        ]));
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();
        $response = $this->post(route('admin.customers.impersonate.session.logout'));

        $response->assertUnauthorized();
        $response->assertJsonPath('message', 'Impersonator session missing');
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->impersonator = User::factory()->create();
    }
}
