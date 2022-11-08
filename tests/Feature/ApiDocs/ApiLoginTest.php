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
        $user = User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]);

        $response = $this->actingAs($user)->get('/api/documentation');

        $response->assertStatus(200);

        $response = $this->get('/api/documentation');

        $response->assertStatus(401);
    }
}
