<?php

namespace Tests\Feature\Modules\User\Desk;

use Illuminate\Foundation\Testing\WithFaker;
use Modules\Desk\Models\Desk;
use Modules\User\Enums\UserPermission;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    use WithFaker;

    public function test_authorized_user_can_read_users_with_desks()
    {
        $route = '/admin/users?include=desks';

        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::VIEW_USERS));

        $this->brand->makeCurrent();

        $desk = Desk::factory()->create();


        $response = $this->getJson($route);

    }
}
