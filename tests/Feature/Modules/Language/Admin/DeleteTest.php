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

final class DeleteTest extends TestCase
{
    use DatabaseTransactions;

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
        $language = Language::factory()->create();

        $response = $this->patchJson(route('admin.languages.destroy', ['language' => $language->id]));

        $response->assertUnauthorized();
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
