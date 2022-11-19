<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Tests\TestCase;

final class LogoutTest extends TestCase
{
    /**
     * @test
     */
    public function logout_success(): void
    {
        $this->authenticateUser('web');

        $response = $this->post(route('admin.auth.logout'));

        $response->assertNoContent();
        $this->assertGuest();
    }

    /**
     * @test
     */
    public function logout_failed(): void
    {
        $response = $this->post(route('admin.auth.logout'));

        $response->assertUnauthorized();
        $this->assertGuest();
    }
}
