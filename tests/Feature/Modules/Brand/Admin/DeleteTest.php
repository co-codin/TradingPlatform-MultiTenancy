<?php

namespace Tests\Feature\Modules\Brand\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Brand\Enums\BrandPermission;
use Modules\Brand\Models\Brand;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    // use DatabaseTransactions;
    /**
     * Test authorized user can delete brand.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_delete_brand(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::DELETE_BRANDS));

        $brand = Brand::factory()->create();

        $response = $this->deleteJson(route('admin.brands.destroy', ['brand' => $brand->id]));

        $response->assertNoContent();
    }

    /**
     * Test unauthorized user can`t delete brand.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_delete_brand(): void
    {
        $brand = Brand::factory()->create();

        $response = $this->patchJson(route('admin.brands.destroy', ['brand' => $brand->id]));

        $response->assertUnauthorized();
    }
}
