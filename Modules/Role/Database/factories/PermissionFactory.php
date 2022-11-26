<?php
namespace Modules\Role\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Role\Models\Action;
use Modules\Role\Models\Model;
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
        $model = Model::inRandomOrder()->first() ?? Model::factory()->create();
        $action = Action::inRandomOrder()->first() ?? Action::factory()->create();

        return [
            'name' => mb_strtolower("{$action->name} {$model->name}"),
            'model_id' => $model,
            'action_id' => $action,
            'guard_name' => User::DEFAULT_AUTH_GUARD,
        ];
    }
}
