<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User\Department;

use Illuminate\Foundation\Testing\WithFaker;
use Modules\Department\Models\Department;
use Modules\User\Enums\UserPermission;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    use WithFaker;

    public function test_authorized_user_can_read_users_with_departments()
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::VIEW_USERS));

        $this->brand->makeCurrent();

        $department = Department::factory()->create();

        $response = $this->getJson(route('admin.users.index'));

        dd(
            $response->json()
        );
    }
}
