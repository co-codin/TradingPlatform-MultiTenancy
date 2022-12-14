<?php

namespace Tests\Feature\Modules\Brand\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Modules\Brand\Enums\BrandPermission;
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

        $data = Brand::factory()->make();

        $response = $this->json('POST', route('admin.brands.store'), $data->toArray());

        $response->assertCreated();

        $this->assertDatabaseHas('brands', [
            'name' => $data['name'],
        ]);

        $response->assertJson([
            'data' => $data->toArray(),
        ]);

        $this->assertTrue(DB::select("SELECT EXISTS(SELECT schema_name FROM information_schema.schemata WHERE schema_name='{$data['database']}')")[0]->exists);
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
     * Test brand domain exist.
     *
     * @return void
     *
     * @test
     */
    public function brand_domain_exist(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $brand = Brand::factory()->create();

        $data = Brand::factory()->make(['domain' => $brand->domain]);

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
     * Test brand database required.
     *
     * @return void
     *
     * @test
     */
    public function brand_dabase_is_required(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $data = Brand::factory()->make()->toArray();
        unset($data['database']);

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
     * Test brand domain is string.
     *
     * @return void
     *
     * @test
     */
    public function brand_domain_is_string(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $data = Brand::factory()->make();
        $data->domain = 1;

        $response = $this->postJson(route('admin.brands.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test brand database is string.
     *
     * @return void
     *
     * @test
     */
    public function brand_logo_url_is_string(): void
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::CREATE_BRANDS));

        $data = Brand::factory()->make();
        $data->database = 1;

        $response = $this->postJson(route('admin.brands.store'), $data->toArray());

        $response->assertUnprocessable();
    }
}
