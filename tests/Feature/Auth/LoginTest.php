<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Modules\User\Models\WorkerInfo;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class LoginTest extends BrandTestCase
{
    use HasAuth;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUser(User::factory()->create([
            'password' => Hash::make('password'),
        ]));
    }

    /**
     * @test
     */
    public function success(): void
    {
        $user = $this->getUser();
        $this->brand->makeCurrent();
        WorkerInfo::factory()->create(['user_id' => $user->id]);
        $response = $this->post(route('admin.auth.login'), [
            'login' => $user->getEmail(),
            'password' => 'password',
        ]);

        $response->assertNoContent();
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     *
     * @depends success
     */
    public function remember_success(): void
    {
        $user = $this->getUser();
        $this->brand->makeCurrent();
        WorkerInfo::factory()->create(['user_id' => $user->id]);
        $response = $this->post(route('admin.auth.login'), [
            'login' => $user->getEmail(),
            'password' => 'password',
            'remember_me' => true,
        ]);

        $response->assertNoContent();
        $this->assertAuthenticatedAs($user);

        $response->assertCookie(auth()->guard('web')->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));
    }

    /**
     * @test
     */
    public function login_failed(): void
    {
        $user = $this->getUser();
        $this->brand->makeCurrent();
        WorkerInfo::factory()->create(['user_id' => $user->id]);
        $response = $this->post(route('admin.auth.login'), [
            'login' => $user->getEmail(),
            'password' => 'random',
        ]);

        $response->assertUnprocessable();
    }
}
