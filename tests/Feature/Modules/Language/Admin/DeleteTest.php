<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Language\Admin;

use Modules\Language\Enums\LanguagePermission;
use Modules\Language\Models\Language;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can delete language.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_delete_language(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::DELETE_LANGUAGES));

        $this->brand->makeCurrent();

        $language = Language::factory()->create();

        $response = $this->deleteJson(route('admin.languages.destroy', ['language' => $language->id]));

        $response->assertNoContent();
    }

    /**
     * Test unauthorized user can`t delete language.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_delete_language(): void
    {
        $this->brand->makeCurrent();

        $language = Language::factory()->create();

        $response = $this->patchJson(route('admin.languages.destroy', ['language' => $language->id]));

        $response->assertUnauthorized();
    }
}
