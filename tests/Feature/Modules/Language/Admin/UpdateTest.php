<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Language\Admin;

use Modules\Language\Enums\LanguagePermission;
use Modules\Language\Models\Language;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    /**
     * Test authorized user can update language.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_update_language(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::EDIT_LANGUAGES));

        $language = Language::factory()->create();
        $data = Language::factory()->make();

        $response = $this->patchJson(route('admin.languages.update', ['language' => $language->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'name' => $data['name'],
                'code' => $data['code'],
            ],
        ]);
    }

    /**
     * Test unauthorized user can`t update language.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_update_language(): void
    {
        $language = Language::factory()->create();
        $data = Language::factory()->make();

        $response = $this->patchJson(route('admin.languages.update', ['language' => $language->id]), $data->toArray());

        $response->assertUnauthorized();
    }
}
