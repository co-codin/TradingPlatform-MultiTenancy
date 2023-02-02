<?php

declare(strict_types=1);

namespace Tests\Feature\ApiDocs;

use Modules\User\Models\WorkerInfo;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class ApiLoginTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function api_login(): void
    {
        $password = 'password';

        $this->brand->makeCurrent();
        WorkerInfo::factory()->create(['user_id' => $this->user->id]);

        $response = $this->get('/api/documentation', [
            'Authorization' => 'Basic '.base64_encode("{$this->user->getEmail()}:{$password}"),
        ]);

        $response->assertOk();
    }
}
