<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Token;

use Tests\TestCase;

final class DeleteTest extends TestCase
{
    /**
     * @test
     */
    public function delete_success(): void
    {
        $this->authenticateUser();
        $user = $this->getUser();
        $name = 'token';
        $user->createToken($name);
        $response = $this->delete(route('admin.token.delete'), [
            'token_name' => $name,
        ]);

        $response->assertNoContent();
        $this->assertNull($user->tokens()->where('name', $name)->first());
    }

    /**
     * @test
     */
    public function delete_not_found(): void
    {
        $this->authenticateUser();
        $user = $this->getUser();
        $name = 'token';
        $response = $this->delete(route('admin.token.delete'), [
            'token_name' => $name,
        ]);

        $response->assertNotFound();
        $this->assertNull($user->tokens()->where('name', $name)->first());
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $response = $this->delete(route('admin.token.delete'));

        $response->assertUnauthorized();
    }
}
