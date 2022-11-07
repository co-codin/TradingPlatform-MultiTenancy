<?php
declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');

        User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]);
    }

    public function test_login_endpoint(): void
    {
        $response = $this->json('POST', route('admin.auth.login'), $this->getRightData());

        $response->assertStatus(200);

        $response = $this->json('POST', route('admin.auth.login'), $this->getWrongData());

        $response->assertStatus(422);
    }


    public function test_me_endpoint(): void
    {
        $response = $this->json('POST', route('admin.auth.login'), $this->getRightData());

        $token = $response->json('token');

        $response = $this->withToken($token)->json('GET', route('admin.auth.user'));

        dd(
            $response->requ()
        );

        $response->assertStatus(200);

        $this->assertNotEmpty($response->json());
    }

    /**
     * @depends test_login_endpoint
     */
    public function test_logout(): void
    {
        $response = $this->json('POST', route('admin.auth.login'), $this->getRightData());

        $token = $response->json('token');

        $response = $this->withToken($token)->json('POST', route('admin.auth.logout'));

        $response->assertStatus(202);
        $this->assertEmpty(session()->get('access_token'));
    }

    /**
     * @depends test_logout
     */
    public function test_failed_me_endpoint(): void
    {
        $response = $this->json('GET', route('admin.auth.user'));

        $response->assertStatus(401);
    }

    protected function getRightData(): array
    {
        return [
            'email' => 'test@admin.com',
            'password' => 'admin1',
        ];
    }

    protected function getWrongData(): array
    {
        return [
            'email' => 'adm@stoxtech.com',
            'password' => 'admin1',
        ];
    }
}
