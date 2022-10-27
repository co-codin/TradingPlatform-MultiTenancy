<?php


namespace Tests\Feature\Modules\Role\Web\Role;


use Modules\Role\Models\Role;
use Tests\TestCase;

class ReadTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticateAdmin();
    }

    public function test_user_can_view_roles()
    {
        Role::factory()->count($count = 5)->create();

        $response = $this->json('GET', route('admin.roles.index'));

        $response->assertOk();
        $this->assertEquals($count + 1, count(($response['data'])));
        $response->assertJsonStructure([
            'data' => [
                [
                    "id",
                    "name",
                    "guard_name",
                    "key",
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

    public function test_user_can_view_single_role()
    {
        $role = Role::factory()->create();

        $response = $this->json('GET', route('roles.show', $role));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                "id",
                "name",
                "guard_name",
                "key",
                "created_at",
                "updated_at",
            ]
        ]);
    }
}
