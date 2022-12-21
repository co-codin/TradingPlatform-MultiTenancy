<?php

declare(strict_types=1);

namespace Modules\Customer\Password;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Symfony\Component\HttpFoundation\Response;
use Tests\BrandTestCase;

final class ClientForgetTest extends BrandTestCase
{
    use TenantAware;

    /**
     * @test
     */
    public function accepted(): void
    {
        $this->brand->makeCurrent();
        $customer = $this->brand->execute(
            fn () => Customer::factory()->make(['password' => Hash::make('password')])
        );
        $customer->save();

        $response = $this->post(route('customer.auth.password.forget'), ['email' => $customer->email]);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertContent(Password::RESET_LINK_SENT);
    }

    /**
     * @test
     */
    public function throttled(): void
    {
        $this->brand->makeCurrent();
        $customer = $this->brand->execute(
            fn () => Customer::factory()->make(['password' => Hash::make('password')])
        );
        $customer->save();

        $data = ['email' => $customer->email];
        $this->post(route('customer.auth.password.forget'), $data);
        $response = $this->post(route('customer.auth.password.forget'), $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', Password::RESET_THROTTLED);
    }

    /**
     * @test
     */
    public function banned(): void
    {
        $this->brand->makeCurrent();
        $customer = $this->brand->execute(
            fn () => Customer::factory()->make([
                'password' => Hash::make('password'),
                'banned_at' => CarbonImmutable::now(),
            ])
        );
        $customer->save();

        $response = $this->post(route('customer.auth.password.forget'), ['email' => $customer->email]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor('banned');
    }

    /**
     * @test
     */
    public function unprocessable(): void
    {
        $this->brand->makeCurrent();
        $response = $this->post(route('customer.auth.password.forget'), ['email' => 'test@non-existent.test']);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertContent(Password::RESET_LINK_SENT);
    }
}
