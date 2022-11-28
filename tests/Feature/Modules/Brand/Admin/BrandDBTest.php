<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Brand\Admin;

use Modules\Brand\Enums\BrandPermission;
use Modules\Brand\Services\BrandDBService;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class BrandDBTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function test_import(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::VIEW_BRANDS));

        $this->brand->users()->attach($this->getUser());

        $response = $this->import([
            BrandDBService::ALLOWED_MODULES['Department'],
            BrandDBService::ALLOWED_MODULES['Desk'],
            BrandDBService::ALLOWED_MODULES['Geo'],
            BrandDBService::ALLOWED_MODULES['Language'],
            BrandDBService::ALLOWED_MODULES['Role'],
            BrandDBService::ALLOWED_MODULES['Token'],
            BrandDBService::ALLOWED_MODULES['User'],
        ]);

        $response->assertStatus(ResponseAlias::HTTP_ACCEPTED);
    }
}
