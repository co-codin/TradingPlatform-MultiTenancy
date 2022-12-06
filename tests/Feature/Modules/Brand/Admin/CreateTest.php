<?php

namespace Tests\Feature\Modules\Brand\Admin;

use App\Jobs\CreateTenantDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Modules\Brand\Enums\BrandPermission;
use Modules\Brand\Jobs\MigrateStructureJob;
use Modules\Brand\Models\Brand;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test authorized user can create brand.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_brand(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));
//
        $data = Brand::factory()->make();

        $response = $this->json('POST', route('admin.brands.store'), $data->toArray());

        dd(
            $response->json()
        );

//        $response->assertCreated();
//
//        $this->assertDatabaseHas('brands', [
//            'name' => $data['name']
//        ]);
//
//        $response->assertJson([
//            'data' => $data->toArray(),
//        ]);
    }

    /**
     * Test unauthorized user can`t create brand.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_create_brand(): void
    {
        $data = Brand::factory()->make();

        $response = $this->post(route('admin.brands.store'), $data->toArray());

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
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $brand = Brand::factory()->create();

        $data = Brand::factory()->make(['slug' => $brand->slug]);

        $response = $this->postJson(route('admin.brands.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test brand name required.
     *
     * @return void
     *
     * @test
     */
    public function brand_name_is_required(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $data = Brand::factory()->make()->toArray();
        unset($data['name']);

        $response = $this->postJson(route('admin.brands.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test brand title required.
     *
     * @return void
     *
     * @test
     */
    public function brand_title_is_required(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $data = Brand::factory()->make()->toArray();
        unset($data['title']);

        $response = $this->postJson(route('admin.brands.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test brand slug required.
     *
     * @return void
     *
     * @test
     */
    public function brand_slug_is_required(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $data = Brand::factory()->make()->toArray();
        unset($data['slug']);

        $response = $this->postJson(route('admin.brands.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test brand logo_url required.
     *
     * @return void
     *
     * @test
     */
    public function brand_logo_url_is_required(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $data = Brand::factory()->make()->toArray();
        unset($data['logo_url']);

        $response = $this->postJson(route('admin.brands.store'), $data);

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
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $data = Brand::factory()->make();
        $data->name = 1;

        $response = $this->postJson(route('admin.brands.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test brand name is string.
     *
     * @return void
     *
     * @test
     */
    public function brand_title_is_string(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $data = Brand::factory()->make();
        $data->title = 1;

        $response = $this->postJson(route('admin.brands.store'), $data->toArray());

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
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $data = Brand::factory()->make();
        $data->slug = 1;

        $response = $this->postJson(route('admin.brands.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test brand slug is string.
     *
     * @return void
     *
     * @test
     */
    public function brand_logo_url_is_string(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $data = Brand::factory()->make();
        $data->logo_url = 1;

        $response = $this->postJson(route('admin.brands.store'), $data->toArray());

        $response->assertUnprocessable();
    }
}
