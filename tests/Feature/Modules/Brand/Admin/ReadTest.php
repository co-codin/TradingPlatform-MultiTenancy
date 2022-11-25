<?php

namespace Tests\Feature\Modules\Brand\Admin;

use Illuminate\Http\Response;
use Modules\Brand\Enums\BrandPermission;
use Modules\Brand\Models\Brand;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;
use Tests\Traits\HasAuth;

class ReadTest extends TestCase
{
    /**
     * @test
     */
    public function test_unauthenticated_user_cannot_view_brands()
    {
        $response = $this->get(route('admin.brands.index'));


        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function test_unauthorized_user_cannot_view_brands()
    {
        $this->authenticateUser();

        $response = $this->get(route('admin.brands.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function test_authorized_user_can_view_brands()
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::VIEW_BRANDS));

        Brand::factory()->count(5)->create();

        $response = $this->get(route('admin.brands.index'));

        $response->assertSuccessful();

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'title',
                    'slug',
                    'logo_url',
                    'is_active',
                    'updated_at',
                    'created_at',
                ]
            ],
            'links' => [
                "first",
                "last",
                "prev",
                "next",
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links' => [
                    [
                        'url',
                        'label',
                        'active',
                    ]
                ],
                'path',
                'per_page',
                'to',
                'total',
            ]
        ]);
    }

    /**
     * @test
     */
    public function test_authorized_user_can_view_single_brand()
    {
        $this->authenticateWithPermission(BrandPermission::fromValue(BrandPermission::VIEW_BRANDS));

        $brand = Brand::factory()->create();

        $response = $this->get(route('admin.brands.show', $brand));

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'title',
                'slug',
                'logo_url',
                'is_active',
                'updated_at',
                'created_at',
            ]
        ]);
    }
}
