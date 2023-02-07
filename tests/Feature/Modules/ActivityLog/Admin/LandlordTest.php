<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\ActivityLog\Admin;

use Illuminate\Support\Arr;
use Modules\ActivityLog\Models\ActivityLog;
use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class LandlordTest extends BrandTestCase
{
    use HasAuth;
    use TenantAware;

    /**
     * @test
     */
    public function comparing_logs_with_output_data(): void
    {
        $this->authenticateWithPermission(
            SplitterPermission::fromValue(SplitterPermission::CREATE_SPLITTER)
        );

        $this->brand->makeCurrent();

        $data = Splitter::factory()->addSplitterChoiceData()->make(['brand_id' => $this->brand->id])->toArray();

        $response = $this->post(route('admin.splitter.store'), $data);

        $response->assertOk();

        $log = ActivityLog::whereCauserType('Modules\User\Models\User')
            ->whereCauserId($this->user->id)
            ->whereDescription('created')
            ->first();

        $response->assertJson([
            'data' => Arr::except(json_decode($log->properties, true), ['attributes']),
        ]);
    }
}
