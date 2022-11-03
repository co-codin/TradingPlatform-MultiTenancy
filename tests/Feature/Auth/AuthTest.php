<?php


namespace Tests\Feature\Auth;


use Modules\Worker\Models\User;
use Modules\Worker\Models\Worker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected static ?User $user = null;

    protected function setUp(): void
    {
        parent::setUp();

        Worker::factory()->create([
            'email' => 'admin@stoxtech.com'
        ]);
    }

    public function test_login_endpoint()
    {
        $response = $this->json('POST', route('auth.login'), $this->getRightData());

        $response->assertStatus(200);

        $response = $this->json('POST', route('auth.login'), $this->getWrongData());

        $response->assertStatus(404);
    }

    public function test_me_endpoint()
    {
        $response = $this->json('POST', route('auth.login'), $this->getRightData());

        $token = $response->json('token');

        $response = $this->withToken($token)->json('GET', route('auth.user'));

        $response->assertStatus(200);

        $this->assertNotEmpty($response->json());
    }

    public function test_failed_me_endpoint()
    {
        $response = $this->json('GET', route('auth.user'));

        $response->assertStatus(401);
    }

    public function test_logout()
    {
        $response = $this->json('POST', route('auth.login'), $this->getRightData());

        $token = $response->json('token');

        $response = $this->withToken($token)->json('POST', route('auth.logout'));

        $response->assertStatus(200);
        $this->assertEmpty(session()->get('access_token'));
    }

    protected function getRightData()
    {
        return [
            'email' => 'admin@stoxtech.com',
            'password' => 'admin1',
        ];
    }

    protected function getWrongData()
    {
        return [
            'email' => 'adm@stoxtech.com',
            'password' => 'admin1',
        ];
    }
}
