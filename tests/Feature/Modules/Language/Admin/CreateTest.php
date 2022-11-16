<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Language\Admin;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Language\Enums\LanguagePermission;
use Modules\Language\Models\Language;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    use DatabaseTransactions;

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

        $data = Language::factory()->make();

        $response = $this->postJson(route('admin.languages.store'), $data->toArray());

        $response->assertCreated();

        $response->assertJson([
            'data' => [
                'name' => $data['name'],
                'code' => $data['code'],
            ],
        ]);
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

        $data = Language::factory()->make();
        $data->code = 1;

        $response = $this->postJson(route('admin.languages.store'), $data->toArray());

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
