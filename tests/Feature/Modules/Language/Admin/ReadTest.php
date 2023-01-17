<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Language\Admin;

use Modules\Language\Enums\LanguagePermission;
use Modules\Language\Models\Language;
use Tests\TestCase;

final class ReadTest extends TestCase
{
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

        Language::truncate();
        Language::factory()->create();

        $response = $this->getJson(route('admin.languages.index'));

        $response->assertOk();
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

        Language::truncate();
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
        $language = Language::factory()->create();

        $response = $this->getJson(route('admin.languages.show', ['language' => $language->id]));

        $response->assertUnauthorized();
    }
}
