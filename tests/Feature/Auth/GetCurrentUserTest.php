<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Tests\TestCase;

final class GetCurrentUserTest extends TestCase
{
    /**
     * @test
     */
    public function me_endpoint(): void
    {
        $this->authenticateUser('web');

        $response = $this->get(route('admin.auth.user'));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'permissions',
                'role',
            ],
        ]);
    }

    /**
     * @test
     */
    public function failed_me_endpoint(): void
    {
        $response = $this->get(route('admin.auth.user'));

        $response->assertUnauthorized();
    }
}
