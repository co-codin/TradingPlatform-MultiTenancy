<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Language\Admin;

use Modules\Language\Enums\LanguagePermission;
use Modules\Language\Models\Language;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can get languages list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_languages_list(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::VIEW_LANGUAGES));

        $this->brand->makeCurrent();

        $language = Language::factory()->create();

        $response = $this->getJson(route('admin.languages.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                $language->toArray(),
            ],
        ]);
    }

    /**
     * Test unauthorized user cant get languages list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_get_languages_list(): void
    {
        $this->brand->makeCurrent();

        Language::factory()->create();

        $response = $this->getJson(route('admin.languages.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get languages list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_language(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::VIEW_LANGUAGES));

        $this->brand->makeCurrent();

        $language = Language::factory()->create();

        $response = $this->getJson(route('admin.languages.show', ['language' => $language->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => $language->toArray(),
        ]);
    }

    /**
     * Test unauthorized user cant get languages list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_get_language(): void
    {
        $this->brand->makeCurrent();

        $language = Language::factory()->create();

        $response = $this->getJson(route('admin.languages.show', ['language' => $language->id]));

        $response->assertUnauthorized();
    }
}
