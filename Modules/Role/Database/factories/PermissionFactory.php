<?php

namespace Modules\Role\Database\factories;

use App\Models\Action;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\User;

class PermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Role\Models\Permission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $model = Model::factory()->create();
        $action = Action::factory()->create();

        return [
            'name' => mb_strtolower("{$action->name} {$model->name}"),
            'model_id' => $model,
            'action_id' => $action,
            'guard_name' => User::DEFAULT_AUTH_GUARD,
        ];
    }
}
