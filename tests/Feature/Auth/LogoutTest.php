<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Tests\TestCase;

final class LogoutTest extends TestCase
{
    /**
     * @test
     */
    public function success(): void
    {
        $this->authenticateUser('web');

        $response = $this->post(route('admin.auth.logout'));

        $response->assertNoContent();
        $this->assertGuest();
    }

    /**
     * @test
     */
    public function failed(): void
    {
        $response = $this->post(route('admin.auth.logout'));

        $response->assertUnauthorized();
        $this->assertGuest();
    }
}
