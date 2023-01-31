<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Splitter\Admin;

use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(
            SplitterPermission::fromValue(SplitterPermission::CREATE_SPLITTER)
        );

        $this->brand->makeCurrent();

        $data = Splitter::factory()->addSplitterChoiceData()->make(['brand_id' => $this->brand->id])->toArray();

        $response = $this->post(route('admin.splitter.store'), $data);

        $response->assertOk();
        $response->assertJson(['data' => $data]);
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $data = Splitter::factory()->addSplitterChoiceData()->make(['brand_id' => $this->brand->id])->toArray();

        $response = $this->post(route('admin.splitter.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.splitter.store'));

        $response->assertUnauthorized();
    }
}
