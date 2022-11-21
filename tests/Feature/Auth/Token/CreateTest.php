<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Token;

use Tests\TestCase;

final class CreateTest extends TestCase
{
    /**
     * @test
     */
    public function create_success(): void
    {
        $this->authenticateUser();
        $user = $this->getUser();
        $name = 'token';
        $response = $this->post(route('admin.token.create'), [
            'token_name' => $name,
        ]);

        $response->assertCreated();
        $response->assertJsonStructure(['token']);
        $this->assertNotNull($user->tokens()->where('name', $name)->first());
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $response = $this->post(route('admin.token.create'));

        $response->assertUnauthorized();
    }
}
