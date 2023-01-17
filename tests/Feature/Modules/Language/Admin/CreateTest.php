<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Language\Admin;

use Modules\Language\Enums\LanguagePermission;
use Modules\Language\Models\Language;
use Tests\TestCase;

final class CreateTest extends TestCase
{
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
        $data = Language::factory()->make();

        $response = $this->postJson(route('admin.languages.store'), $data->toArray());

        $response->assertUnauthorized();
    }
}
