<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Language\Admin;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Modules\Language\Enums\LanguagePermission;
use Modules\Language\Models\Language;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    use DatabaseTransactions;

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

    /**
     * Test language name exist.
     *
     * @return void
     *
     * @test
     */
    public function language_name_exist(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::EDIT_LANGUAGES));

        $language = Language::factory()->create();
        $targetLanguage = Language::factory()->create();
        $data = Language::factory()->make(['name' => $language->name]);

        $response = $this->patchJson(route('admin.languages.update', ['language' => $targetLanguage->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test language code exist.
     *
     * @return void
     *
     * @test
     */
    public function language_code_exist(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::EDIT_LANGUAGES));

        $language = Language::factory()->create();
        $targetLanguage = Language::factory()->create();
        $data = Language::factory()->make(['code' => $language->code]);

        $response = $this->patchJson(route('admin.languages.update', ['language' => $targetLanguage->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test language name filled.
     *
     * @return void
     *
     * @test
     */
    public function language_name_is_filled(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::EDIT_LANGUAGES));

        $language = Language::factory()->create();
        $data = Language::factory()->make(['name' => null])->toArray();

        $response = $this->patchJson(route('admin.languages.update', ['language' => $language->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test language code filled.
     *
     * @return void
     *
     * @test
     */
    public function language_code_is_filled(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::EDIT_LANGUAGES));

        $language = Language::factory()->create();
        $data = Language::factory()->make(['code' => null])->toArray();

        $response = $this->patchJson(route('admin.languages.update', ['language' => $language->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test language name is string.
     *
     * @return void
     *
     * @test
     */
    public function language_name_is_string(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::EDIT_LANGUAGES));

        $language = Language::factory()->create();
        $data = Language::factory()->make();
        $data->name = 1;

        $response = $this->patchJson(route('admin.languages.update', ['language' => $language->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test language code is string.
     *
     * @return void
     *
     * @test
     */
    public function language_code_is_string(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::EDIT_LANGUAGES));

        $language = Language::factory()->create();
        $data = Language::factory()->make();
        $data->code = 1;

        $response = $this->patchJson(route('admin.languages.update', ['language' => $language->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call(sprintf(
            'brand-migrate --path=%s',
            "Modules/Brand/DB/Migrations/create_languages_table.php",
        ));
    }
}
