<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Splitter\Admin;

use Modules\Desk\Models\Desk;
use Modules\Splitter\Enums\SplitterChoiceType;
use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Modules\Splitter\Models\SplitterChoice;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class PutDesksTest extends BrandTestCase
{
    use HasAuth;
    use TenantAware;

    /**
     * @test
     */
    public function can_put_desks(): void
    {
        $this->authenticateWithPermission(
            SplitterPermission::fromValue(SplitterPermission::EDIT_SPLITTER)
        );

        $this->brand->makeCurrent();

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(['type' => SplitterChoiceType::DESK]), 'splitterChoice')
            ->create(['user_id' => $this->user->id]);

        $splitterChoice = $splitter->splitterChoice()->first();

        $response = $this->put(route('admin.splitter-choice.desk', ['splitter_choice' => $splitterChoice->id]), [
            'desks' => [[
                'id' => (Desk::first() ?? Desk::factory()->create())->id,
                'cap_per_day' => 1,
                'percentage_per_day' => 20,
            ]],
        ]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function can_not_put_desks(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(['type' => SplitterChoiceType::DESK]), 'splitterChoice')
            ->create(['user_id' => $this->user->id]);

        $splitterChoice = $splitter->splitterChoice()->first();

        $response = $this->put(route('admin.splitter-choice.desk', ['splitter_choice' => $splitterChoice->id]), [
            'desks' => [[
                'id' => (Desk::first() ?? Desk::factory()->create())->id,
                'cap_per_day' => 1,
                'percentage_per_day' => 20,
            ]],
        ]);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(['type' => SplitterChoiceType::DESK]), 'splitterChoice')
            ->create(['user_id' => $this->user->id]);

        $splitterChoice = $splitter->splitterChoice()->first();

        $response = $this->put(route('admin.splitter-choice.desk', ['splitter_choice' => $splitterChoice->id + 9]), [
            'desks' => [[
                'id' => (Desk::first() ?? Desk::factory()->create())->id,
                'cap_per_day' => 1,
                'percentage_per_day' => 20,
            ]],
        ]);

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(['type' => SplitterChoiceType::DESK]), 'splitterChoice')
            ->create();

        $splitterChoice = $splitter->splitterChoice()->first();

        $response = $this->put(route('admin.splitter-choice.desk', ['splitter_choice' => $splitterChoice->id + 9]), [
            'desks' => [[
                'id' => (Desk::first() ?? Desk::factory()->create())->id,
                'cap_per_day' => 1,
                'percentage_per_day' => 20,
            ]],
        ]);

        $response->assertUnauthorized();
    }
}
