<?php
declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\User\Models\User;
use Tests\TestCase;

class ForgetTest extends TestCase
{
    protected string $testEmail = 'test@admin.com';

    public function test_forget_endpoint(): void
    {
        $response = $this->json('POST', route('admin.auth.forget'), $this->getRighData());

        $response->assertStatus(202);

        $response = $this->json('POST', route('admin.auth.forget'), ['email' => 'adm@test.test']);

        $response->assertStatus(422);
    }

    /**
     * @depends test_forget_endpoint
     */
    public function test_reset_endpoint(): void
    {
        $user = User::where(['email' => $this->testEmail])->first();

        $token = Password::createToken($user);

        // Test bad token
        $response = $this->json(
            'POST',
            route('password.reset', ['token' => Str::random()]),
            $this->getRighData()
        );

        $response->assertStatus(400);

        // Test wrong data
        $response = $this->json(
            'POST',
            route('password.reset', ['token' => $token]),
            $this->getWrongData()
        );

        $response->assertStatus(422);

        // Test success reset
        $response = $this->json(
            'POST',
            route('password.reset', ['token' => $token]),
            $this->getRighData()
        );

        $response->assertStatus(202);
    }

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create([
            'email' => $this->testEmail,
            'password' => Hash::make('admin1'),
        ]);
    }

    protected function getRighData(): array
    {
        return [
            'email' => $this->testEmail,
            'password' => 'admin123456',
            'password_confirmation' => 'admin123456',
        ];
    }

    protected function getWrongData(): array
    {
        return [
            'email' => $this->testEmail,
            'password' => 'admin123456',
            'password_confirmation' => 'admin123452',
        ];
    }
}
