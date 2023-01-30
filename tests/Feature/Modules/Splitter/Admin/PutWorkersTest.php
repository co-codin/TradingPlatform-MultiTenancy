<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Splitter\Admin;

use Modules\Splitter\Enums\SplitterChoiceType;
use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Modules\Splitter\Models\SplitterChoice;
use Modules\User\Models\User;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class PutWorkersTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function can_put_worker(): void
    {
        $this->authenticateWithPermission(
            SplitterPermission::fromValue(SplitterPermission::EDIT_SPLITTER)
        );

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(['type' => SplitterChoiceType::WORKER]), 'splitterChoice')
            ->create(['user_id' => $this->user->id]);

        $splitterChoice = $splitter->splitterChoice()->first();

        $response = $this->put(route('admin.splitter-choice.worker', ['splitter_choice' => $splitterChoice->id]), [
            'workers' => [[
                'id' => User::first()->id,
                'cap_per_day' => 1,
                'percentage_per_day' => 20,
            ]],
        ]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function can_not_put_worker(): void
    {
        $this->authenticateUser();

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(['type' => SplitterChoiceType::WORKER]), 'splitterChoice')
            ->create(['user_id' => $this->user->id]);

        $splitterChoice = $splitter->splitterChoice()->first();

        $response = $this->put(route('admin.splitter-choice.worker', ['splitter_choice' => $splitterChoice->id]), [
            'workers' => [[
                'id' => User::first()->id,
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

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(['type' => SplitterChoiceType::WORKER]), 'splitterChoice')
            ->create(['user_id' => $this->user->id]);

        $splitterChoice = $splitter->splitterChoice()->first();

        $response = $this->put(route('admin.splitter-choice.worker', ['splitter_choice' => $splitterChoice->id + 9]), [
            'workers' => [[
                'id' => User::first()->id,
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
        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(['type' => SplitterChoiceType::WORKER]), 'splitterChoice')
            ->create();

        $splitterChoice = $splitter->splitterChoice()->first();

        $response = $this->put(route('admin.splitter-choice.worker', ['splitter_choice' => $splitterChoice->id + 9]), [
            'workers' => [[
                'id' => User::first()->id,
                'cap_per_day' => 1,
                'percentage_per_day' => 20,
            ]],
        ]);

        $response->assertUnauthorized();
    }
}
