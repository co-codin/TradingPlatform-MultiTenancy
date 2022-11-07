<?php


namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Hash;
use Modules\Worker\Models\Worker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected Worker $worker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->worker = Worker::factory()->create([
            'password' => Hash::make('admin1'),
        ]);
    }

    public function test_login_endpoint()
    {
        $this->withoutExceptionHandling();

        $response = $this->json('POST', route('admin.auth.login'), $this->getRightData());

        $response->assertStatus(200);

        $response = $this->json('POST', route('admin.auth.login'), $this->getWrongData());

        $response->assertStatus(400);
    }

    public function test_me_endpoint()
    {
        $response = $this->json('POST', route('admin.auth.login'), $this->getRightData());

        $token = $response->json('token');

        $response = $this->withToken($token)->json('GET', route('admin.auth.user'));

        $response->assertStatus(200);

        $this->assertNotEmpty($response->json());
    }

    public function test_failed_me_endpoint()
    {
        $response = $this->json('GET', route('admin.auth.user'));

        $response->assertStatus(401);
    }

    public function test_logout()
    {
        $response = $this->json('POST', route('admin.auth.login'), $this->getRightData());

        $token = $response->json('token');

        $response = $this->withToken($token)->json('POST', route('admin.auth.logout'));

        $response->assertStatus(200);
        $this->assertEmpty(session()->get('access_token'));
    }

    protected function getRightData(): array
    {
        return [
            'email' => $this->worker->email,
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
