<?php


namespace Tests\Feature\Modules\Role\Web\Permission;


use Modules\Role\Models\Permission;
use Tests\TestCase;

class ReadTest extends TestCase
{
    public function test_user_can_view_permissions()
    {
        $this->authenticateAdmin();

        Permission::factory()->count($count = 5)->create();

        $response = $this->json('GET', route('permissions.index'));

        $response->assertOk();
        $this->assertEquals($count, count(($response['data'])));
        $response->assertJsonStructure([
            'data' => [
                [
                    "id",
                    "name",
                    "description",
                    "guard_name",
                    "created_at",
                    "updated_at",
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

    public function test_user_can_view_single_permissions()
    {
        $this->authenticateAdmin();

        $permission =  Permission::factory()->create();

        $response = $this->json('GET', route('permissions.show', $permission));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "description",
                "guard_name",
                "created_at",
                "updated_at",
            ]
        ]);
    }
}
