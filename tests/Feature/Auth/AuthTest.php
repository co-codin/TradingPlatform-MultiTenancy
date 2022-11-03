<?php


namespace Tests\Feature\Auth;

use Modules\Worker\Models\Worker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected static ?Worker $user = null;

    protected function setUp(): void
    {
        parent::setUp();

        Worker::factory()->create([
            'password' => 'admin'
        ]);
    }

    public function test_login_endpoint()
    {
        static::$user = Worker::factory()->create();

        $response = $this->json('POST', route('auth.login'));

        $response->assertStatus(200);

        $response = $this->json('POST', route('auth.login'), $this->getWrongData());

        $response->assertStatus(400);
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
