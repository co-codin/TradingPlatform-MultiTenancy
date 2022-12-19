<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User\Associations;

use Modules\Language\Models\Language;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class AssociateLanguageTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $this->brand->makeCurrent();

        $user = $this->getUser();
        $response = $this->put(route('admin.users.language.update', ['id' => $user->id]), [
            'languages' => [
                Language::factory()->create(),
                Language::factory()->create(),
            ],
        ]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();

        $this->brand->makeCurrent();

        $response = $this->put(route('admin.users.language.update', ['id' => $user->id]), [
            'languages' => [
                Language::factory()->create(),
            ],
        ]);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $this->brand->makeCurrent();

        $userId = User::orderByDesc('id')->first()?->id + 1 ?? 1;
        $response = $this->put(route('admin.users.language.update', ['id' => $userId]), [
            'languages' => [
                Language::factory()->create(),
            ],
        ]);

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $response = $this->put(route('admin.users.language.update', ['id' => 1]));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeCurrentTenantAndSetHeader();
    }
}
