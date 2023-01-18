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
        $model = Model::factory()->make();

        if (Model::where('name', $model->name)->exists()) {
            $model = Model::where('name', $model->name)->first();
        } else {
            $model->save();
        }

        $action = Action::factory()->make();

        if (Action::where('name', $action->name)->exists()) {
            $action = Action::where('name', $action->name)->first();
        } else {
            $action->save();
        }

        return [
            'name' => mb_strtolower("{$action->name} {$model->name}"),
            'model_id' => $model->id,
            'action_id' => $action->id,
            'guard_name' => User::DEFAULT_AUTH_GUARD,
        ];
    }
}
