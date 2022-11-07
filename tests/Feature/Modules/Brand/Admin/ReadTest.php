<?php

namespace Tests\Feature\Modules\Brand\Admin;

use Modules\Brand\Enums\BrandPermission;
use Modules\Role\Models\Permission;
use Modules\Worker\Models\Worker;
use Tests\TestCase;

class ReadTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $worker = Worker::factory()->create();

        $permission = Permission::factory()->create([
            'name' => BrandPermission::VIEW_BRANDS,
        ]);

        $worker->givePermissionTo($permission->name);

        $response = $this->json('POST', route('admin.auth.login'), [
            'email' => 'admin@medeq.ru',
            'password' => 'admin1',
        ]);

        $this->withToken($response->json('token'));
    }

    public function test_unauthenticated_or_unauthorized_user_cannot_view_brands()
    {
        
    }

    public function test_authorized_user_can_view_brands()
    {

    }
}
