<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Splitter\Admin;

use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Modules\Splitter\Models\SplitterChoice;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(
            SplitterPermission::fromValue(SplitterPermission::EDIT_SPLITTER)
        );

        $this->brand->makeCurrent();

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(), 'splitterChoice')
            ->create(['brand_id' => $this->brand->id]);

        $splitterData = Splitter::factory()->addSplitterChoiceData()->make()->toArray();

        $response = $this->patchJson(route('admin.splitter.update', ['splitter' => $splitter->id]), $splitterData);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => array_keys($splitterData),
        ]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(), 'splitterChoice')
            ->create(['brand_id' => $this->brand->id]);

        $data = Splitter::factory()->addSplitterChoiceData()->make();

        $response = $this->patch(
            route('admin.splitter.update', ['splitter' => $splitter]),
            $data->toArray()
        );

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $splitterId = Splitter::orderByDesc('id')->first()?->id + 1 ?? 1;

        $data = Splitter::factory()->addSplitterChoiceData()->make();

        $response = $this->patch(
            route('admin.splitter.update', ['splitter' => $splitterId]),
            $data->toArray()
        );

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(), 'splitterChoice')
            ->create();

        $response = $this->patch(route('admin.splitter.update', ['splitter' => $splitter]));

        $response->assertUnauthorized();
    }
}
