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

final class ReadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * {@inheritDoc}
     */
    public function actingAs(UserContract $user, $guard = null): TestCase
    {
        return parent::actingAs($user, $guard ?: User::DEFAULT_AUTH_GUARD);
    }

    /**
     * Test authorized user can get languages list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_languages_list(): void
    {
        $language = Language::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('admin.languages.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                [
                    'id' => $language->id,
                    'name' => $language->name,
                    'code' => $language->code,
                ],
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
        $language = Language::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('admin.languages.show', ['language' => $language->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $language->id,
                'name' => $language->name,
                'code' => $language->code,
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
    public function unauthorized_user_cant_get_language(): void
    {
        $language = Language::factory()->create();

        $response = $this->getJson(route('admin.languages.show', ['language' => $language->id]));

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

        $this->user = User::factory()->create()
            ->givePermissionTo(Permission::factory()->create([
                'name' => LanguagePermission::VIEW_LANGUAGES,
            ]));
    }
}
