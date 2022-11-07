<?php


namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Hash;
use Modules\Worker\Models\Worker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Worker::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]);
    }

    protected static Worker $worker;

    public function test_login_endpoint()
    {
        $response = $this->json('POST', route('admin.auth.login'), $this->getRightData());

        $response->assertStatus(200);

        $response = $this->json('POST', route('admin.auth.login'), $this->getWrongData());

        $response->assertStatus(422);
    }


    public function test_me_endpoint()
    {
        $this->withoutExceptionHandling();

        $response = $this->json('POST', route('admin.auth.login'), $this->getRightData());

        $token = $response->json('token');

        $response = $this->withToken($token)->json('GET', route('admin.auth.user'));

        $response->assertStatus(200);

        $this->assertNotEmpty($response->json());
    }

    /**
     * @depends test_login_endpoint
     */
    public function test_logout()
    {
        $response = $this->actingAs(static::$worker)->json('POST', route('admin.auth.logout'));

        $response->assertStatus(200);
        $this->assertEmpty(session()->get('access_token'));
    }

    /**
     * @depends test_logout
     */
    public function test_failed_me_endpoint()
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
