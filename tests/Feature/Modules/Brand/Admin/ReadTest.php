<?php

namespace Tests\Feature\Modules\Brand\Admin;

use Illuminate\Http\Response;
use Modules\Brand\Enums\BrandPermission;
use Modules\Brand\Models\Brand;
use Modules\Role\Models\Permission;
use Modules\Worker\Models\Worker;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class ReadTest extends TestCase
{
    protected Worker $worker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->worker = Worker::factory()->create([
            'email' => 'admin@admin.com'
        ]);

//        $permission = Permission::factory()->create([
//            'name' => BrandPermission::VIEW_BRANDS,
//        ]);
//
//        $worker->givePermissionTo($permission->name);
//
//        $response = $this->json('POST', route('admin.auth.login'), [
//            'email' => 'admin@admin.com',
//            'password' => 'admin',
//        ]);
//
//        $this->withToken($response->json('token'));
    }

    public function test_unauthenticated_user_cannot_view_brands()
    {
        $response = $this->json('GET', route('admin.brands.index'));

        $response->assertStatus(ResponseAlias::HTTP_UNAUTHORIZED);
    }

    public function test_unauthorized_user_cannot_view_brands()
    {
        $response = $this->json('POST', route('admin.auth.login'), [
            'email' => 'admin@admin.com',
            'password' => 'admin',
        ]);

        $response = $this->withToken($response->json('token'))
            ->json('GET', route('admin.brands.index'));


        dd(
            auth('sanctum')->user()
        );

        $response->assertStatus(ResponseAlias::HTTP_FORBIDDEN);
    }

    public function test_authorized_user_can_view_brands()
    {
        $permission = Permission::factory()->create([
            'name' => BrandPermission::VIEW_BRANDS,
        ]);

        $this->worker->givePermissionTo($permission->name);

        Brand::factory()->count($count = 5)->create();

        $response = $this->actingAs($this->worker)->json('GET', route('admin.brands.index'));

        $response->assertOk();
        $this->assertCount($count, ($response['data']));
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

    public function test_authorized_user_can_view_single_brand()
    {
        $permission = Permission::factory()->create([
            'name' => BrandPermission::VIEW_BRANDS,
        ]);

        $this->worker->givePermissionTo($permission->name);

        $brand = Brand::factory()->create();

        $response = $this->actingAs($this->worker)->json('GET', route('admin.brands.show', $brand));

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
