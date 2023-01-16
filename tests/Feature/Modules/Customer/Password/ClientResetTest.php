<?php

declare(strict_types=1);

namespace Modules\Customer\Password;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\Customer\Models\Customer;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Symfony\Component\HttpFoundation\Response;
use Tests\BrandTestCase;

final class ClientResetTest extends BrandTestCase
{
    use TenantAware;

    private const BROKER = 'customers';

    protected string $testEmail = 'test@admin.com';
    private readonly Customer $customer;

    /**
     * @test
     */
    public function accepted(): void
    {
        $response = $this->post(route('customer.auth.password.reset'), [
            'email' => $this->customer->email,
            'password' => 'Password%',
            'password_confirmation' => 'Password%',
            'token' => Password::broker(self::BROKER)->createToken($this->customer),
        ]);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertContent(Password::PASSWORD_RESET);
    }

    /**
     * @test
     */
    public function invalid_token(): void
    {
        $response = $this->post(route('customer.auth.password.reset'), [
            'email' => $this->customer->email,
            'password' => 'Password%',
            'password_confirmation' => 'Password%',
            'token' => Str::random(),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', Password::INVALID_TOKEN);
    }

    /**
     * @test
     */
    public function invalid_user(): void
    {
        $response = $this->post(route('customer.auth.password.reset'), [
            'email' => 'test@non-existent.test',
            'password' => 'Password%',
            'password_confirmation' => 'Password%',
            'token' => Password::broker(self::BROKER)->createToken($this->customer),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', Password::INVALID_USER);
    }

    /**
     * @test
     */
    public function unprocessable(): void
    {
        $response = $this->post(route('customer.auth.password.reset'), [
            'email' => 'test',
            'password' => 'Password%',
            'password_confirmation' => 'Password%',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['token', 'email', 'password']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->brand->makeCurrent();
        $this->customer = $this->brand->execute(
            fn () => Customer::factory()->make(['password' => Hash::make('password')])
        );
        $this->customer->save();
    }
}
