<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Splitter\Admin;

use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Modules\Splitter\Models\SplitterChoice;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use HasAuth;
    use TenantAware;

    /**
     * @test
     */
    public function can_view_any(): void
    {
        $this->authenticateWithPermission(SplitterPermission::fromValue(SplitterPermission::VIEW_SPLITTER));

        $this->brand->makeCurrent();

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(), 'splitterChoice')
            ->create(['brand_id' => $this->brand->id]);

        $response = $this->getJson(route('admin.splitter.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [$splitter->toArray()],
        ]);
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();

        $response = $this->get(route('admin.splitter.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_view(): void
    {
        $this->authenticateWithPermission(
            SplitterPermission::fromValue(SplitterPermission::VIEW_SPLITTER)
        );

        $this->brand->makeCurrent();

        $splitter = Splitter::factory()
            ->has(SplitterChoice::factory(), 'splitterChoice')
            ->create(['brand_id' => $this->brand->id]);

        $response = $this->get(route('admin.splitter.show', ['splitter' => $splitter]));

        $response->assertOk();
        $response->assertJson(['data' => $splitter->toArray()]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $splitter = Splitter::factory()->create();

        $response = $this->get(route('admin.splitter.show', ['splitter' => $splitter]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $splitterId = Splitter::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.splitter.show', ['splitter' => $splitterId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.splitter.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $splitter = Splitter::factory()->create();

        $response = $this->get(route('admin.splitter.show', ['splitter' => $splitter]));

        $response->assertUnauthorized();
    }
}
