<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User\DisplayOption;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;
use Tests\TestCase;

final class ReadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function admin_can_view_list(): void
    {
        $this->authenticateAdmin();

        $displayOptions = DisplayOption::factory(10)->create([
            'user_id' => $this->getUser()->id,
        ]);

        $response = $this->get(route('admin.users.display-options.index', ['worker' => $this->getUser()->id]));

        $response->assertOk();
        $response->assertJson([
            'data' => $displayOptions->only('id')->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_list(): void
    {
        $this->authenticateUser();

        $response = $this->get(route('admin.users.display-options.index', ['worker' => $this->getUser()->id]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized_view_list(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.display-options.index', ['worker' => $user->id]));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function admin_can_view(): void
    {
        $this->authenticateAdmin();

        $displayOption = DisplayOption::factory()->create();

        $response = $this->get(route('admin.users.display-options.show', ['worker' => $this->getUser()->id, 'display_option' => $displayOption]));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $displayOption = DisplayOption::factory()->create();

        $this->authenticateUser();

        $response = $this->get(route('admin.users.display-options.show', ['worker' => $this->getUser()->id, 'display_option' => $displayOption]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function user_can_view_not_found(): void
    {
        $this->authenticateAdmin();

        $displayOptionId = DisplayOption::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.users.display-options.show', ['worker' => $this->getUser()->id, 'display_option' => $displayOptionId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $user = User::factory()->create();
        $displayOption = DisplayOption::factory()->create();

        $response = $this->get(route('admin.users.display-options.show', ['worker' => $user->id, 'display_option' => $displayOption]));

        $response->assertUnauthorized();
    }
}
