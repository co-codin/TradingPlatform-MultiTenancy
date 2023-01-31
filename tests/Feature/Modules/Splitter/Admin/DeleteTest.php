<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Splitter\Admin;

use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Modules\Splitter\Models\SplitterChoice;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function can_delete(): void
    {
        $this->authenticateWithPermission(SplitterPermission::fromValue(SplitterPermission::DELETE_SPLITTER));

        $this->brand->makeCurrent();

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(), 'splitterChoice')
            ->create(['brand_id' => $this->brand->id]);

        $response = $this->deleteJson(route('admin.splitter.destroy', ['splitter' => $splitter->id]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function can_not_delete(): void
    {
        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(), 'splitterChoice')
            ->create();

        $response = $this->patchJson(route('admin.splitter.destroy', ['splitter' => $splitter->id]));

        $response->assertUnauthorized();
    }
}
