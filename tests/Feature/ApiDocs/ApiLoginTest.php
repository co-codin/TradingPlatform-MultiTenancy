<?php
declare(strict_types=1);

namespace Tests\Feature\ApiDocs;

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Tests\TestCase;

class ApiLoginTest extends TestCase
{
    public function test_api_login(): void
    {
        $password = 'password';

        $user = User::factory()->create([
            'email' => 'test@stoxtech.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->get('/api/documentation', [
            'Authorization' => 'Basic '. base64_encode("{$user->email}:{$password}")
        ]);

        $response->assertOk();
    }
}
