<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\ActivityLog\Admin;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\ActivityLog\Models\ActivityLog;
use Modules\Department\Enums\DepartmentPermission;
use Modules\Department\Models\Department;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class TenantTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    use WithFaker;

    /**
     * Test authorized user can create department.
     *
     * @return void
     *
     * @test
     */
    public function comparing_logs_with_output_data(): void
    {
        $this->authenticateWithPermission(DepartmentPermission::fromValue(DepartmentPermission::CREATE_DEPARTMENTS));

        $this->brand->makeCurrent();

        $data = Department::factory()->make()->toArray();

        $response = $this->post(route('admin.departments.store'), $data);

        $response->assertCreated();

        $log = ActivityLog::whereCauserType('Modules\User\Models\User')
            ->whereCauserId($this->user->id)
            ->whereDescription('created')
            ->first();

        $response->assertJson([
            'data' => Arr::except(json_decode($log->properties, true), ['attributes']),
        ]);
    }
}
