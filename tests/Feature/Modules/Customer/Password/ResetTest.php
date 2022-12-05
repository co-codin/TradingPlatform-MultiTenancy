<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer\Password;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class ResetTest extends TestCase
{
    protected string $testEmail = 'test@admin.com';

    /**
     * @test
     */
    public function accepted(): void
    {
        $this->authenticateWithPermission(
            CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS)
        );

        $customer = Customer::factory()->create([
            'email' => $this->testEmail,
            'password' => Hash::make('admin1'),
        ]);

        $response = $this->post(route('customers.password.reset'), [
            'email' => $customer->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'token' => Password::createToken($customer),
            'send_email' => true,
        ]);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertContent(Password::PASSWORD_RESET);
    }

    /**
     * @test
     */
    public function accepted_without_send_email(): void
    {
        $this->authenticateWithPermission(
            CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS)
        );

        $customer = Customer::factory()->create([
            'email' => $this->testEmail,
            'password' => Hash::make('admin1'),
        ]);

        $response = $this->post(route('customers.password.reset'), [
            'email' => $customer->email,
            'password' => $password = 'password123',
            'password_confirmation' => 'password123',
            'token' => Password::createToken($customer),
            'send_email' => false,
        ]);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'password' => $password,
            ],
        ]);
    }

    /**
     * @test
     */
    public function invalid_token(): void
    {
        $this->authenticateWithPermission(
            CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS)
        );

        $customer = Customer::factory()->create([
            'email' => $this->testEmail,
            'password' => Hash::make('admin1'),
        ]);

        $response = $this->post(route('customers.password.reset'), [
            'email' => $customer->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
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
        $this->authenticateWithPermission(
            CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS)
        );

        $customer = Customer::factory()->create([
            'email' => $this->testEmail,
            'password' => Hash::make('admin1'),
        ]);

        $response = $this->post(route('customers.password.reset'), [
            'email' => 'test@non-existent.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'token' => Password::createToken($customer),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', Password::INVALID_USER);
    }

    /**
     * @test
     */
    public function unprocessable(): void
    {
        $this->authenticateWithPermission(
            CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS)
        );

        $response = $this->post(route('customers.password.reset'), [
            'email' => 'test',
            'password' => 'password123',
            'password_confirmation' => 'password1234',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['token', 'email', 'password']);
    }
}
