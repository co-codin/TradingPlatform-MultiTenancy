<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Language\Admin;

use Modules\Language\Enums\LanguagePermission;
use Modules\Language\Models\Language;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can create language.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_language(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::CREATE_LANGUAGES));

        $this->brand->makeCurrent();

        $data = Language::factory()->make()->toArray();

        $response = $this->postJson(route('admin.languages.store'), $data);

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }

    /**
     * Test unauthorized user can`t create language.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_create_language(): void
    {
        $this->brand->makeCurrent();

        $data = Language::factory()->make();

        $response = $this->postJson(route('admin.languages.store'), $data->toArray());

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
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::CREATE_LANGUAGES));

        $this->brand->makeCurrent();

        $language = Language::factory()->create();

        $data = Language::factory()->make(['name' => $language->name]);

        $response = $this->postJson(route('admin.languages.store'), $data->toArray());

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
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::CREATE_LANGUAGES));

        $this->brand->makeCurrent();

        $language = Language::factory()->create();

        $data = Language::factory()->make(['code' => $language->code]);

        $response = $this->postJson(route('admin.languages.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test language name required.
     *
     * @return void
     *
     * @test
     */
    public function language_name_is_required(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::CREATE_LANGUAGES));

        $this->brand->makeCurrent();

        $data = Language::factory()->make()->toArray();
        unset($data['name']);

        $response = $this->postJson(route('admin.languages.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test language code required.
     *
     * @return void
     *
     * @test
     */
    public function language_code_is_required(): void
    {
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::CREATE_LANGUAGES));

        $this->brand->makeCurrent();

        $data = Language::factory()->make()->toArray();
        unset($data['code']);

        $response = $this->postJson(route('admin.languages.store'), $data);

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
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::CREATE_LANGUAGES));

        $this->brand->makeCurrent();

        $data = Language::factory()->make();
        $data->name = 1;

        $response = $this->postJson(route('admin.languages.store'), $data->toArray());

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
        $this->authenticateWithPermission(LanguagePermission::fromValue(LanguagePermission::CREATE_LANGUAGES));

        $this->brand->makeCurrent();

        $data = Language::factory()->make();
        $data->code = 1;

        $response = $this->postJson(route('admin.languages.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * {@inheritDoc}
     */
    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     Artisan::call(sprintf(
    //         'brand-migrate --path=%s',
    //         "Modules/Brand/DB/Migrations/create_languages_table.php",
    //     ));
    // }
}
