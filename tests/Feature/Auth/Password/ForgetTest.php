<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Password;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class ForgetTest extends TestCase
{
    /**
     * @test
     */
    public function accepted(): void
    {
        $user = User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]);
        $response = $this->post(route('admin.auth.password.forget'), ['email' => $user->email]);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertContent(Password::RESET_LINK_SENT);
    }

    /**
     * @test
     */
    public function throttled(): void
    {
        $user = User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]);
        $data = ['email' => $user->email];
        $this->post(route('admin.auth.password.forget'), $data);
        $response = $this->post(route('admin.auth.password.forget'), $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', Password::RESET_THROTTLED);
    }

    /**
     * @test
     */
    public function banned(): void
    {
        $user = User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
            'banned_at' => CarbonImmutable::now(),
        ]);
        $response = $this->post(route('admin.auth.password.forget'), ['email' => $user->email]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor('banned');
    }

    /**
     * @test
     */
    public function unprocessable(): void
    {
        $response = $this->post(route('admin.auth.password.forget'), ['email' => 'test@non-existent.test']);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertContent(Password::RESET_LINK_SENT);
    }
}
