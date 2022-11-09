<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]);
    }

    public function test_login_endpoint(): void
    {
        $response = $this->json('POST', route('admin.auth.login'), $this->getRightData());

        $response->assertStatus(Response::HTTP_OK);

        $response = $this->json('POST', route('admin.auth.login'), $this->getWrongData());

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_me_endpoint(): void
    {
        $response = $this->json('POST', route('admin.auth.login'), $this->getRightData());

        $this->withToken($response->json('token'));

        $response = $this->json('GET', route('admin.auth.user'));

        $response->assertStatus(Response::HTTP_OK);

        $this->assertNotEmpty($response->json());
    }

    /**
     * @depends test_login_endpoint
     */
    public function test_logout(): void
    {
        $response = $this->json('POST', route('admin.auth.login'), $this->getRightData());

        $response = $this->withToken($response->json('token'))->json('POST', route('admin.auth.logout'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @depends test_logout
     */
    public function test_failed_me_endpoint(): void
    {
        $response = $this->json('GET', route('admin.auth.user'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    private function getRightData(): array
    {
        return [
            'email' => 'test@admin.com',
            'password' => 'admin1',
        ];
    }

    private function getWrongData(): array
    {
        return [
            'email' => 'adm@stoxtech.com',
            'password' => 'admin1',
        ];
    }
}
