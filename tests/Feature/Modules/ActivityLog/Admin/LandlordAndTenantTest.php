<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\ActivityLog\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Modules\ActivityLog\Models\ActivityLog;
use Modules\Role\Models\Role;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Modules\User\Models\WorkerInfo;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class LandlordAndTenantTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function comparing_logs_with_output_data(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::CREATE_USERS));

        $data = User::factory()->withParent()
            ->withAffiliate()
            ->raw(['password' => self::$basePassword, 'is_active' => fake()->boolean]);

        $data['roles'] = [
            [
                'id' => Role::factory()->create()->id,
            ],
        ];

        $data['worker_info'] = WorkerInfo::factory()->raw();

        $this->brand->makeCurrent();

        Event::fake();

        $response = $this->post('/admin/workers', $data);

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
