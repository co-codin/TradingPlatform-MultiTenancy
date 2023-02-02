<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Password;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\User\Models\User;
use Modules\User\Models\WorkerInfo;
use Symfony\Component\HttpFoundation\Response;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ForgetTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function accepted(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $this->brand->makeCurrent();
        WorkerInfo::factory()->create(['user_id' => $user->id]);
        $response = $this->post(route('admin.auth.password.forget'), ['email' => $user->getEmail()]);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertContent(Password::RESET_LINK_SENT);
    }

    /**
     * @test
     */
    public function throttled(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $this->brand->makeCurrent();
        WorkerInfo::factory()->create(['user_id' => $user->id]);
        $data = ['email' => $user->getEmail()];
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
            'password' => Hash::make('password'),
            'banned_at' => CarbonImmutable::now(),
        ]);
        $this->brand->makeCurrent();
        WorkerInfo::factory()->create(['user_id' => $user->id]);
        $response = $this->post(route('admin.auth.password.forget'), ['email' => $user->getEmail()]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrorFor('banned');
    }

    /**
     * @test
     */
    public function unprocessable(): void
    {
        $this->brand->makeCurrent();
        $response = $this->post(route('admin.auth.password.forget'), ['email' => 'test@non-existent.test']);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertContent(Password::RESET_LINK_SENT);
    }
}
