<?php

namespace Tests\Feature\Modules\Brand\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Brand\Enums\BrandPermission;
use Modules\Brand\Models\Brand;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can update brand.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_update_brand(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::EDIT_BRANDS));

        $brand = Brand::factory()->create();
        $data = Brand::factory()->make();

        $response = $this->patchJson(route('admin.brands.update', ['brand' => $brand->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => $data->toArray(),
        ]);
    }

    /**
     * Test unauthorized user can`t update brand.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_update_brand(): void
    {
        $brand = Brand::factory()->create();
        $data = Brand::factory()->make();

        $response = $this->patchJson(route('admin.brands.update', ['brand' => $brand->id]), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test brand slug exist.
     *
     * @return void
     *
     * @test
     */
    public function brand_slug_exist(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::EDIT_BRANDS));

        $brand = Brand::factory()->create();
        $targetBrand = Brand::factory()->create();
        $data = Brand::factory()->make(['slug' => $brand->slug]);

        $response = $this->patchJson(route('admin.brands.update', ['brand' => $targetBrand->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test brand name filled.
     *
     * @return void
     *
     * @test
     */
    public function brand_name_is_filled(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::EDIT_BRANDS));

        $brand = Brand::factory()->create();
        $data = Brand::factory()->make(['name' => null])->toArray();

        $response = $this->patchJson(route('admin.brands.update', ['brand' => $brand->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test brand name filled.
     *
     * @return void
     *
     * @test
     */
    public function brand_title_is_filled(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::EDIT_BRANDS));

        $brand = Brand::factory()->create();
        $data = Brand::factory()->make(['name' => null])->toArray();

        $response = $this->patchJson(route('admin.brands.update', ['brand' => $brand->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test brand slug filled.
     *
     * @return void
     *
     * @test
     */
    public function brand_slug_is_filled(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::EDIT_BRANDS));

        $brand = Brand::factory()->create();
        $data = Brand::factory()->make(['slug' => null])->toArray();

        $response = $this->patchJson(route('admin.brands.update', ['brand' => $brand->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test brand logo_url filled.
     *
     * @return void
     *
     * @test
     */
    public function brand_logo_url_is_filled(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::EDIT_BRANDS));

        $brand = Brand::factory()->create();
        $data = Brand::factory()->make(['logo_url' => null])->toArray();

        $response = $this->patchJson(route('admin.brands.update', ['brand' => $brand->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test brand name is string.
     *
     * @return void
     *
     * @test
     */
    public function brand_name_is_string(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::EDIT_BRANDS));

        $brand = Brand::factory()->create();
        $data = Brand::factory()->make();
        $data->name = 1;

        $response = $this->patchJson(route('admin.brands.update', ['brand' => $brand->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test brand title is string.
     *
     * @return void
     *
     * @test
     */
    public function brand_title_is_string(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::EDIT_BRANDS));

        $brand = Brand::factory()->create();
        $data = Brand::factory()->make();
        $data->title = 1;

        $response = $this->patchJson(route('admin.brands.update', ['brand' => $brand->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test brand slug is string.
     *
     * @return void
     *
     * @test
     */
    public function brand_slug_is_string(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::EDIT_BRANDS));

        $brand = Brand::factory()->create();
        $data = Brand::factory()->make();
        $data->slug = 1;

        $response = $this->patchJson(route('admin.brands.update', ['brand' => $brand->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test brand logo_url is string.
     *
     * @return void
     *
     * @test
     */
    public function brand_logo_url_is_string(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::EDIT_BRANDS));

        $brand = Brand::factory()->create();
        $data = Brand::factory()->make();
        $data->logo_url = 1;

        $response = $this->patchJson(route('admin.brands.update', ['brand' => $brand->id]), $data->toArray());

        $response->assertUnprocessable();
    }
}
