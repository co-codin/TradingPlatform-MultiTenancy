<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer\Password;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
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

        $response = $this->post(route('admin.customers.password.reset', ['customer' => $customer]), [
            'password' => 'password123',
            'password_confirmation' => 'password123',
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

        $response = $this->post(route('admin.customers.password.reset', ['customer' => $customer]), [
            'password' => $password = 'password123',
            'password_confirmation' => 'password123',
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
    public function notFound(): void
    {
        $this->authenticateWithPermission(
            CustomerPermission::fromValue(CustomerPermission::EDIT_CUSTOMERS)
        );

        $customerId = Customer::orderByDesc('id')->first()?->id ?? 1;

        $response = $this->post(route('admin.customers.password.reset', ['customer' => $customerId]), [
            'password' => 'password123',
            'password_confirmation' => 'password1234',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['token', 'email', 'password']);
    }
}
