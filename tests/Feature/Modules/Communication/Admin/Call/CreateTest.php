<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Communication\Admin\Call;

use Modules\Communication\Enums\CallPermission;
use Modules\Communication\Models\Call;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can create call.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_call(): void
    {
        $this->authenticateWithPermission(CallPermission::fromValue(CallPermission::CREATE_COMMUNICATION_CALL));

        $this->brand->makeCurrent();

        $data = Call::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->postJson(route('admin.communication.call.store'), $data);

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }

    /**
     * Test authorized user can`t create call.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_create_call(): void
    {
        $this->authenticateUser();

        $this->brand->makeCurrent();

        $data = Call::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->postJson(route('admin.communication.call.store'), $data);

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user can`t create call.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $data = Call::factory()->make()->toArray();

        $this->brand->makeCurrent();

        $response = $this->postJson(route('admin.communication.call.store'), $data);

        $response->assertUnauthorized();
    }
}
