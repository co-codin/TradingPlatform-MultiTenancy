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

    /**
     * {@inheritDoc}
     */
    public function state($state): Factory
    {
        if (isset($state['name'])) {
            [$actionName, $modelName] = explode(' ', $state['name']);

            $state['action_id'] ??= $this->actionByName($actionName);
            $state['model_id'] ??= $this->modelByName($modelName);
        }

        if (isset($state['action_id']) && isset($state['model_id'])) {
            $action = Action::find($state['action_id']);
            $model = Model::find($state['model_id']);

            $state['name'] ??= "{$action->name} {$model->name}";
        }

        return parent::state($state);
    }

    /**
     * Get or factory action by name.
     *
     * @param string $name
     * @return int|null
     */
    private function actionByName(string $name): ?int
    {
        return (Action::where('name', $name)->first() ?? Action::factory()->create(['name' => $name]))?->id;
    }

    /**
     * Get or factory model by name.
     *
     * @param string $name
     * @return int|null
     */
    private function modelByName(string $name): ?int
    {
        return (Model::where('name', $name)->first() ?? Model::factory()->create(['name' => $name]))?->id;
    }
}
