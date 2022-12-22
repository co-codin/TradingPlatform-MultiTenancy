<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Call;

use Modules\Communication\Enums\CallPermission;
use Modules\Communication\Models\Call;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can update template.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_call(): void
    {
        $this->authenticateWithPermission(CallPermission::fromValue(CallPermission::EDIT_COMMUNICATION_CALL));

        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $data = Call::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.communication.call.update', ['call' => $call->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => $data->toArray(),
        ]);
    }

    /**
     * Test authorized user can`t update call.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_cant_update_call(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $data = Call::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.communication.call.update', ['call' => $call->id]), $data->toArray());

        $response->assertForbidden();
    }

    /**
     * Test authorized user can update not found call.
     *
     * @return void
     *
     * @test
     */
    final public function authorized_user_can_update_not_found_call(): void
    {
        $this->authenticateWithPermission(CallPermission::fromValue(CallPermission::EDIT_COMMUNICATION_CALL));

        $this->brand->makeCurrent();

        $callId = Call::orderByDesc('id')->first()?->id + 1 ?? 1;

        $data = Call::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.communication.call.update', ['call' => $callId]), $data->toArray());

        $response->assertNotFound();
    }

    /**
     * Test unauthorized user can update call.
     *
     * @return void
     *
     * @test
     */
    final public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $call = $this->brand->execute(function () {
            return Call::factory()->make();
        });
        $call->save();

        $data = Call::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->patchJson(route('admin.communication.call.update', ['call' => $call->id]), $data->toArray());

        $response->assertUnauthorized();
    }
}
